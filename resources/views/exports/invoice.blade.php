<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $transaction->code }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h2 { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .kop { margin-bottom: 20px; text-align: center; }
        .kop img { width: 100px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .total { font-weight: bold; text-align: right; }
        .footer { margin-top: 20px; text-align: center; }
        .catatan { margin-top: 20px; font-style: italic; }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ public_path('logo.jpg') }}" alt="Logo">
        <h2>Mts Walisongo Asy-Syirbaany</h2>
        <p>Jl Betawi Kp Gunung No.86 RT.5/16 Kel. Jombang Kec. Ciputat, Kota Tangerang Selatan, Banten 15414.</p>
    </div>

    <h2>Invoice Pembayaran</h2>

    <p><strong>Kode Transaksi:</strong> {{ $transaction->code }}</p>
    <p><strong>Nama:</strong> {{ $transaction->student->nama }}</p>
    <p><strong>NIS:</strong> {{ $transaction->student->nis }}</p>
    <p><strong>Kelas:</strong> {{ $transaction->student->kelas }}</p>
    <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($transaction->payment_date)->format('d-m-Y') }}</p> 

    <table>
        <thead>
            <tr>
                <th>Jenis Pembayaran</th>
                <th>Bulan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->departments->name }}</td>
                <td>{{ $transaction->departments->month }}</td>
                <td>Rp {{ number_format($transaction->departments->cost, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="total">Total</td>
                <td class="total">Rp {{ number_format($transaction->departments->cost, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p><strong>Status:</strong> {{ ucfirst($transaction->payment_status) }}</p>
    <p><strong>Metode:</strong> {{ $transaction->payment_method }}</p>

    <div class="catatan">
        <<strong>Catatan:</strong> 
        <p>- Harap simpan invoice ini sebagai bukti pembayaran.</p>
        <p>- Uang yang sudah dibayarkan tidak dapat dikembalikan.</p>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembayaran Anda!</p>
    </div>
</body>
</html>