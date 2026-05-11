<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian Barang</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2 f2 f2; }
    </style>
</head>
<body>
    <h2>Laporan Stok & Pembelian Barang</h2>
    <p>Tanggal: {{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Harga Beli (Modal)</th>
                <th>Stok</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->product_code }}</td>
                <td>{{ $p->name }}</td>
                <td>Rp {{ number_format($p->purchase_price) }}</td>
                <td>{{ $p->stock }}</td>
                <td>Rp {{ number_format($p->purchase_price * $p->stock) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total Aset (Modal Terendam)</th>
                <th>Rp {{ number_format($totalModalAset) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>