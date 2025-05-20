<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class StudentTransaction extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Transaction History';
    protected int | string | array $columnSpan = 'full';
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function table(Table $table): Table
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        return $table
            ->query(Transaction::with('departments', 'student')
                ->where('student_id', $user->student_id)
                ->orderBy('created_at', 'DESC'))
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Transaction Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Student')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.nis') // Menambahkan kolom NIS
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.kelas') // Menambahkan kolom Kelas
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departments.name')
                    ->label('Pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departments.month')
                    ->label('Bulan'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->width(150)
                    ->height(75),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departments.cost')
                    ->label('Cost')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('Payment')
                    ->label('Payment')
                    ->icon('heroicon-o-credit-card')
                    ->url(fn ($record) => url("admin/payment/{$record->id}"))
                    ->visible(fn ($record) => $record->payment_status === 'pending'),
            ]);
    }
}

