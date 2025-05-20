<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Pembayaran SPP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 30px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-weight: bold;
            color: #2c3e50;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        .header h3 {
            margin-top: 10px;
            font-size: 16px;
            color: #000;
            letter-spacing: 0.5px;
        }
        .periode {
            font-style: italic;
            font-size: 12px;
            color: #666;
        }
        .print-date {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fdfdfd;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #111;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- Jika ada logo sekolah, masukkan di sini --}}
        {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo Sekolah" height="60"> --}}
        <h2>MTs WALISONGO ASYIRBAANY</h2>
        <p>Jl. Contoh Alamat, Kec. Contoh, Kab. Contoh</p>
        <p>Telp: 0812-XXXX-XXXX</p>
        <h3>LAPORAN TRANSAKSI PEMBAYARAN SPP</h3>
        <p class="periode">Periode: {{ $from ?? '-' }} s/d {{ $until ?? '-' }}</p>
    </div>

    <p class="print-date">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Bulan</th>
                <th>Tagihan</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($transactions as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->student->nama }}</td>
                    <td>{{ $item->student->kelas }}</td>
                    <td>{{ $item->department->month }}</td>
                    <td>Rp {{ number_format($item->department->cost, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($item->payment_method) }}</td>
                    <td>{{ ucfirst($item->payment_status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->payment_date)->translatedFormat('d F Y') }}</td>
                </tr>
                @php $total += $item->department->cost; @endphp
            @endforeach
        </tbody>
    </table>

    <p class="total">Total Tagihan: Rp {{ number_format($total, 0, ',', '.') }}</p>
</body>
</html>
