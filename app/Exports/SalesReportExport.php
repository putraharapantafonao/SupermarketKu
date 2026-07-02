<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return Transaction::with(['user', 'customer'])
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end)
            ->get()
            ->map(function ($transaction) {
                return [
                    'invoice' => $transaction->invoice_number,
                    'kasir' => $transaction->user->name,
                    'pelanggan' => $transaction->customer->name ?? 'Umum',
                    'tanggal' => $transaction->created_at->format('d-m-Y H:i'),
                    'total' => $transaction->grand_total,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Kasir',
            'Pelanggan',
            'Tanggal',
            'Total',
        ];
    }
}
