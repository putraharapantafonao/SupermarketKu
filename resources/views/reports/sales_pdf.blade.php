<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2, p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>SupermarketKu</h2>
    <p>Laporan Penjualan</p>
    <p>Periode: {{ $start }} s/d {{ $end }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->customer->name ?? 'Umum' }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Penjualan: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

</body>
</html>
