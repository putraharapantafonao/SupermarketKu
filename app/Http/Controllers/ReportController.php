<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Exports\SalesReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        Gate::authorize('view-reports');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $start = $request->date('start_date')?->format('Y-m-d') ?? now()->startOfMonth()->format('Y-m-d');
        $end = $request->date('end_date')?->format('Y-m-d') ?? now()->endOfMonth()->format('Y-m-d');

        $transactions = $this->getFilteredTransactions($request)->get();

        $total = $transactions->sum('grand_total');

        return view('reports.sales', compact('transactions', 'total', 'start', 'end'));
    }

    public function salesPdf(Request $request)
    {
        Gate::authorize('view-reports');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $start = $request->date('start_date')?->format('Y-m-d') ?? now()->startOfMonth()->format('Y-m-d');
        $end = $request->date('end_date')?->format('Y-m-d') ?? now()->endOfMonth()->format('Y-m-d');

        $transactions = $this->getFilteredTransactions($request)->get();

        $total = $transactions->sum('grand_total');

        $pdf = Pdf::loadView('reports.sales_pdf', compact('transactions', 'total', 'start', 'end'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function salesExcel(Request $request)
    {
        Gate::authorize('view-reports');

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $start = $request->date('start_date')?->format('Y-m-d') ?? now()->startOfMonth()->format('Y-m-d');
        $end = $request->date('end_date')?->format('Y-m-d') ?? now()->endOfMonth()->format('Y-m-d');

        return Excel::download(new SalesReportExport($start, $end), 'laporan-penjualan.xlsx');
    }

    private function getFilteredTransactions(Request $request)
    {
        $start = $request->date('start_date') ?? now()->startOfMonth();
        $end = $request->date('end_date') ?? now()->endOfMonth();

        return Transaction::with(['user', 'customer'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);
    }
}
