<?php

namespace App\Filament\Pages;

use App\Models\Transaction;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class TransactionReport extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Transaksi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Transaksi';
    protected static ?string $slug = 'transaction-report';

    protected static string $view = 'filament.pages.transaction-report';

    public function getHeaderActions(): array
    {
        return [
            Action::make('Export')
                ->url(route('laporan.transaksi.pdf', request()->only('from', 'until')))
                ->openUrlInNewTab()
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->with(['student', 'department'])->orderBy('payment_date', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.nama')->label('Nama Siswa'),
                Tables\Columns\TextColumn::make('student.kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('department.month')->label('Bulan'),
                Tables\Columns\TextColumn::make('department.cost')->label('Tagihan')->money('IDR'),
                Tables\Columns\TextColumn::make('payment_method')->label('Metode'),
                Tables\Columns\TextColumn::make('payment_status')->badge(),
                Tables\Columns\TextColumn::make('payment_date')->label('Tanggal Bayar')->date(),
            ])
            ->filters([
                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('payment_date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('payment_date', '<=', $data['until']));
                    }),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ])
            ]);
    }
}
