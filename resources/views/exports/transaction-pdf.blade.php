<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Pembayaran SPP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin: 30px;
        }
        .kop {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            justify-content: center;
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop img {
            width: 110px;
            height: auto;
            margin-right: 15px;
        }
        .kop .text {
            text-align: center;
            flex-grow: 1;
        }
        .kop .text h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .kop .text p {
            margin: 2px 0;
            font-size: 12px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .print-date {
            font-size: 12px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="kop">
        <img src="{{ public_path('logo.jpg') }}" alt="Logo">
        <div class="text">
            <h2>MTs Wali Songo Asy-Syirbaany</h2>
            <p>Jl Betawi Kp Gunung No.86 RT.5/16 Kel. Jombang Kec. Ciputat, Kota Tangerang Selatan, Banten 15414.</p>
        </div>
    </div>

    <div class="title">Laporan Transaksi Pembayaran SPP</div>
    <p class="print-date">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
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
                    <td style="text-align: center;">{{ $i + 1 }}</td>
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
