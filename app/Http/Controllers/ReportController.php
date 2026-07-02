<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();

        $transactions = Transaction::with(['user','customer'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->latest()
            ->get();

        $total = $transactions->sum('grand_total');

        return view('reports.sales', compact(
            'transactions',
            'total',
            'start',
            'end'
        ));
    }

    public function salesPdf(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();

        $transactions = Transaction::with(['user', 'customer'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->latest()
            ->get();

        $total = $transactions->sum('grand_total');

        $pdf = Pdf::loadView('reports.sales_pdf', compact(
            'transactions',
            'total',
            'start',
            'end'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function salesExcel(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();

        return Excel::download(
            new SalesReportExport($start, $end),
            'laporan-penjualan.xlsx'
        );
    }
}
