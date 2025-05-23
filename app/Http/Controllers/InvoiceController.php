<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    public function download(Transaction $transaction)
    {
        $user = auth()->user();

        // Hanya siswa yang berhak atas transaksi ini yang bisa mengakses
        if ($user->student_id !== $transaction->student_id) {
            abort(403, 'Akses ditolak: Anda tidak berhak mengakses invoice ini.');
        }

        $pdf = PDF::loadView('exports.invoice', compact('transaction'));
        return $pdf->download("invoice-{$transaction->code}.pdf");
    }
}
