<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $transaction->invoice_number }}</title>

    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.4;
            width: 80mm;
            margin: 0 auto;
            padding: 10px 2px;
            color: #000;
            background-color: #fff;
            text-align: center;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
        }

        td {
            vertical-align: top;
            padding: 1px 0;
        }

        .item-name {
            word-break: break-all;
            display: block;
            margin-top: 4px;
        }

        .btn-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .btn-print {
            background-color: #1e293b;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            font-family: sans-serif;
        }

        @media print {
            .btn-wrapper {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <strong style="font-size: 14px;">SupermarketKu</strong><br>
        Jl. Reulet, Aceh Utara<br>
        Telp: 0812-xxxx-xxxx
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>No. Invoice</td>
            <td>:</td>
            <td class="right">{{ $transaction->invoice_number }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>:</td>
            <td class="right">{{ $transaction->user->name }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>:</td>
            <td class="right">{{ $transaction->customer->name ?? 'Umum' }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td class="right" style="font-size: 10px;">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    @foreach ($transaction->details as $detail)
        <span class="item-name">{{ $detail->product->name }}</span>
        <table style="margin-bottom: 4px;">
            <tr>
                <td style="width: 60%;">{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="right" style="width: 40%;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    @endforeach

    <div class="line"></div>

    <table>
        <tr>
            <td>Total Belanja</td>
            <td class="right">Rp {{ number_format(collect($transaction->details)->sum('subtotal'), 0, ',', '.') }}</td>
        </tr>
        @if($transaction->discount > 0)
            <tr>
                <td>Diskon Transaksi</td>
                <td class="right">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="right"><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">Rp {{ number_format($transaction->payment->paid_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right">Rp {{ number_format($transaction->payment->change_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Metode Bayar</td>
            <td class="right">{{ strtoupper($transaction->payment->method ?? 'CASH') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center" style="margin-top: 8px;">
        Terima Kasih<br>
        Selamat Berbelanja Kembali
    </div>

    <div class="btn-wrapper">
        <button class="btn-print" onclick="window.print()">Cetak Ulang Struk</button>
    </div>

</body>
</html>
