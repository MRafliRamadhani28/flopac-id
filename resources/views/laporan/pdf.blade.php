<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemakaian Stock - Flopac.id</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-table .label {
            width: 150px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-secondary {
            background-color: #6c757d;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMAKAIAN STOCK</h1>
        <p>Flopac.id - Sistem Manajemen Persediaan</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tanggal Cetak:</td>
            <td>{{ date('d/m/Y H:i:s') }}</td>
            <td class="label">Total Data:</td>
            <td>{{ count($groupedData) }} record</td>
        </tr>
        <tr>
            <td class="label">Periode:</td>
            <td colspan="3">Data Pemakaian Stock Tergrupkan per Tanggal dan Barang</td>
        </tr>
    </table>

    @if(count($groupedData) > 0)
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center" style="width: 12%;">Tanggal</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th class="text-center" style="width: 15%;">Warna Barang</th>
                    <th class="text-center" style="width: 10%;">Satuan</th>
                    <th class="text-center" style="width: 13%;">Total Stock Dihabiskan</th>
                    <th class="text-center" style="width: 20%;">Jumlah Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedData as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td class="text-center">
                            <span class="badge badge-secondary">{{ $item->barang->warna }}</span>
                        </td>
                        <td class="text-center">{{ $item->barang->satuan }}</td>
                        <td class="text-center">
                            <span class="badge badge-danger">{{ $item->total_stock_used }}</span>
                        </td>
                        <td class="text-center">{{ $item->total_orders }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <strong>Tidak ada data pemakaian stock yang ditemukan</strong>
        </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }} | Halaman {PAGE_NUM} dari {PAGE_COUNT}</p>
    </div>
</body>
</html>
