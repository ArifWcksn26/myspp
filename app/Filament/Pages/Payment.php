<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield; 
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Transaction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class Payment extends Page
{

    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.payment';

    public $transaction; // Deklarasi properti untuk menyimpan transaksi
    public array $data = []; // Properti untuk menyimpan data form

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Menyembunyikan dari sidebar
    }

    public function mount(int $id): void
    {
        // Ambil transaksi berdasarkan ID
        $this->transaction = Transaction::findOrFail($id);

        // Isi data awal formulir berdasarkan transaksi
        $this->data = [
            'payment_method' => $this->transaction->payment_method ?? null,
            'payment_proof'  => $this->transaction->payment_proof ?? null,
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('payment_method')
                ->label('Metode Pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'transfer' => 'Transfer Bank',
                    'credit_card' => 'Kartu Kredit',
                    'ewallet' => 'E-Wallet',
                    'paypal' => 'PayPal',
                ])
                ->required()
                ->default($this->data['payment_method']), // Menggunakan data awal
        
            FileUpload::make('payment_proof')
                ->label('Bukti Pembayaran')
                ->image() // Mengharuskan format gambar
                ->required()
                ->directory('payment_proofs') // Menentukan direktori penyimpanan
                ->columnSpanFull()
                
        ])->statePath('data');
    }

    public function edit()
    {
       // Validasi data
    $validatedData = $this->form->getState();

    // Hapus file lama jika baru di-upload
    if (isset($validatedData['payment_proof']) && $validatedData['payment_proof'] !== $this->transaction->payment_proof) {
        if ($this->transaction->payment_proof) {
            Storage::delete($this->transaction->payment_proof);
        }
    }

    // Update transaksi
      $this->transaction->update([
        'payment_method' => $validatedData['payment_method'],
        'payment_proof' => $validatedData['payment_proof'],
        'payment_date' => now(), // ⬅️ Tambahkan ini!
        'payment_status' => 'pending', // pastikan status tetap pending
    ]);

    // Kirim notifikasi
    Notification::make()
        ->title('Pembayaran Berhasil!')
        ->body('Terima Kasih Telah Membayar. Mohon Tunggu Persetujuan Oleh Admin.')
        ->success()
        ->send();

    //Redirect ke halaman admin
    return redirect('/admin');
    }
}