<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF; // pastikan ini ada
use Carbon\Carbon;

class ReportController extends Controller
{
    

    public function exportTransactionPDF(Request $request)
    {
        $transactions = Transaction::with(['student', 'department'])
            ->when($request->from, fn ($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->until, fn ($q) => $q->whereDate('payment_date', '<=', $request->until))
            ->orderBy('payment_date', 'desc')
            ->get();

        $pdf = PDF::loadView('exports.transaction-pdf', compact('transactions'));
        return $pdf->download('laporan-transaksi.pdf');
    }
}

