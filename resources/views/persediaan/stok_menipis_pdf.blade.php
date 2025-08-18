<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Menipis</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #222; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #bbb; padding: 8px 10px; text-align: center; }
        th { background: #e6f3fd; color: #016C89; font-weight: 600; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; }
        .bg-warning { background: #fff3cd; color: #856404; }
        .bg-danger { background: #f8d7da; color: #721c24; }
        .bg-success { background: #d4edda; color: #155724; }
        .bg-secondary { background: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <h2>Laporan Stok Menipis</h2>
    <p style="text-align:center; font-size:13px;">Tanggal: {{ date('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Warna</th>
                <th>Satuan</th>
                <th>Safety Stock</th>
                <th>Sisa Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td style="text-align:left; font-weight:500;">{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->barang->warna }}</td>
                <td>{{ $item->barang->satuan }}</td>
                <td><span class="badge bg-secondary">{{ $item->safety_stock }}</span></td>
                <td>
                    <span class="badge bg-{{ $item->getSafetyStockColor() }}">{{ $item->stock }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
