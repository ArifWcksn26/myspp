<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\Department; // Import model Department

class TransactionStats extends StatsOverviewWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Cost Pembayaran', 'Rp ' . number_format($this->getTotalCost(), 0, ',', '.'))
            ->description('Total seluruh biaya transaksi')
            ->icon('heroicon-o-currency-dollar'),
            Stat::make('Total Siswa', number_format($this->getTotalStudents(), 0, ',', '.'))
                ->description('Total siswa terdaftar')
                ->icon('heroicon-o-users'),
            Stat::make('Siswa Sudah Bayar', number_format($this->getTotalStudentsPaid(), 0, ',', '.'))
                ->description('Total siswa yang sudah membayar')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Siswa Belum Bayar', number_format($this->getTotalStudentsUnpaid(), 0, ',', '.'))
                ->description('Total siswa yang belum membayar')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }

    private function getTotalCost(): float
    {
        $totalCost = 0;
        $transactions = Transaction::with('department')->get(); // Eager load the 'department' relationship
        foreach ($transactions as $transaction) {
            $totalCost += $transaction->department->cost;
        }
        return $totalCost;
    }

    private function getTotalStudents(): int
    {
        return Student::count();
    }

    private function getTotalStudentsPaid(): int
    {
        return Transaction::where('payment_status', 'success')->distinct('student_id')->count();
    }

    private function getTotalStudentsUnpaid(): int
    {
        $paidStudentIds = Transaction::where('payment_status', 'success')->distinct('student_id')->pluck('student_id')->toArray();
        return Student::whereNotIn('id', $paidStudentIds)->count();
    }
}
