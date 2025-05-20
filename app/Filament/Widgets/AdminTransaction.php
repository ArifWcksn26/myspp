<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;

class AdminTransaction extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Transaction History Admin';
    protected int | string | array $columnSpan = 'full';
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->with('department')->orderBy('created_at', 'DESC') // Menggunakan with untuk eager loading
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Transaction Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Student')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.nis')
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.kelas')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name') // Ganti 'departments' menjadi 'department'
                    ->label('Pembayaran'),
                Tables\Columns\TextColumn::make('department.month') // Ganti 'departments' menjadi 'department'
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
                ->width(150)  // Set the width of the preview
                    ->height(75)
                ->extraAttributes([
                    'onclick' => 'showImage("'. asset('storage/payment_proofs/{record.payment_proof}') . '")',
                    'style' => 'cursor: pointer;',
                ]),
                Tables\Columns\TextColumn::make('department.cost') // Ganti 'departments' menjadi 'department'
                    ->label('Cost')
                    ->money('IDR'), // Format sebagai uang
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable(),
                    
            ]);
    }
}