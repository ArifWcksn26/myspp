<?php

use App\Filament\Pages\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

route::get('admin/payment/{id}',Payment::class)->name('filament.pages.payment');
Route::get('/laporan/transaksi/pdf', [ReportController::class, 'exportTransactionPDF'])->name('laporan.transaksi.pdf');
Route::get('/invoice/{transaction}/download', [InvoiceController::class, 'download'])
    ->middleware('auth')
    ->name('invoice.download');

