<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'customer', 'payment'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'customer', 'details.product', 'payment']);

        return view('transactions.show', compact('transaction'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['user', 'customer', 'details.product', 'payment']);

        return view('transactions.receipt', compact('transaction'));
    }

    /**
     * Memproses penyimpanan transaksi baru dari POS / Kasir
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar dari Form POS
        $request->validate([
            'method' => 'required|in:cash,qris,transfer,ewallet,debit',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        // Menggunakan Database Transaction agar aman jika salah satu query gagal
        DB::beginTransaction();

        try {
            // 2. Generate Nomor Invoice Otomatis (Contoh: INV-20260624-0001)
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT);

            // 3. Hitung Kalkulasi Total Belanja Kasir
            $totalPrice = 0;
            $detailsData = [];

            foreach ($request->product_id as $index => $productId) {
                $product = Product::findOrFail($productId);
                $qty = $request->quantity[$index];

                // Cek ketersediaan stok fisik produk sebelum transaksi
                if ($product->stock < $qty) {
                    return redirect()->back()->with('error', "Stok untuk produk {$product->name} tidak mencukupi!");
                }

                // FIX SOLUSI: Mengubah dari $product->price menjadi $product->selling_price
                $subtotal = $product->selling_price * $qty;
                $totalPrice += $subtotal;

                // Siapkan array data untuk detail transaksi
                $detailsData[] = [
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->selling_price, // FIX SOLUSI: Menggunakan selling_price agar tidak null
                    'subtotal' => $subtotal,
                ];

                // Potong stok produk secara otomatis
                $product->decrement('stock', $qty);
            }

            // Hitung Grand Total setelah Diskon dan Pajak
            $discount = $request->input('discount', 0);
            $tax = $request->input('tax', 0);
            $grandTotal = ($totalPrice - $discount) + $tax;

            // 4. Simpan Data Utama Transaksi
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(), // ID Kasir yang sedang login
                'customer_id' => $request->customer_id,
                'total_price' => $totalPrice,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ]);

            // 5. Simpan Semua Detail Item Belanjaan
            foreach ($detailsData as $detail) {
                $transaction->details()->create($detail);
            }

            // 6. Simpan Data Informasi Pembayaran (Payments)
            // Menyesuaikan input uang bayar dan kembalian berdasarkan metode
            //  KODE PERBAIKAN AMAN:
            // Jika metode cash tapi input paid_amount kosong atau null, paksa nilainya sama dengan grand total agar tidak null
            $paidAmount = $request->method === 'cash'
                ? ($request->filled('paid_amount') ? $request->input('paid_amount') : $grandTotal)
                : $grandTotal;
            // Hitung kembalian, jika hasilnya minus karena salah input, paksa jadi 0
            $changeAmount = $request->method === 'cash' ? max(0, $paidAmount - $grandTotal) : 0;
                        Payment::create([
                'transaction_id' => $transaction->id,
                'method' => $request->method,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'card_bank' => $request->card_bank, // Akan terisi jika metode 'debit'
                'trace_number' => $request->trace_number, // Akan terisi jika metode 'debit'
                'status' => 'success',
            ]);

            // Jika semua query sukses, kunci data ke database
            DB::commit();

            // 7. Redirect Langsung ke Detail Struk + Bawa Flag Pemicu Otomatis Cetak
            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi Berhasil Disimpan!')
                ->with('print_on_load', true);

        } catch (\Exception $e) {
            // Batalkan semua perubahan jika ada query yang error di tengah jalan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
