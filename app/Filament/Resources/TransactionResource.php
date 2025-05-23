<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Department;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->default(fn() => 'TRX' . mt_rand(10000, 99999)),
                Forms\Components\Select::make('student_id')
                    ->required()
                    ->relationship('student', 'nama'),
                Forms\Components\TextInput::make('payment_status')
                    ->readOnly()
                    ->default('pending'),
                Forms\Components\DatePicker::make('payment_date')
                    ->required(),
                Forms\Components\Fieldset::make('Department')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->required()
                            ->label('Pembayaran & Bulan')
                            ->options(Department::query()->get()->mapWithKeys(function ($department) {
                                return [
                                    $department->id => $department->name . ' - Bulan: ' . $department->month
                                ];
                            })->toArray())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($department = Department::find($state)) {
                                    $set('department_cost', $department->cost);
                                } else {
                                    $set('department_cost', null);
                                }
                            }),
                        Forms\Components\TextInput::make('department_cost')
                            ->label('Cost')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('TRX Code')
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
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.month')
                    ->label('Month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->width(150)  // Set the width of the preview
                    ->height(75),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.cost')
                    ->label('Cost')
                    ->money('IDR') // Format sebagai uang
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Transaction $record): bool => $record->payment_status === 'pending') // Tampilkan hanya jika status pembayaran 'pending'
                    ->action(function (Transaction $record): void {
                        $record->update(['payment_status' => 'success']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Approve Transaction')
                    ->modalSubheading('Are you sure you want to approve this transaction? This action cannot be undone.')
                    ->modalButton('Approve'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
