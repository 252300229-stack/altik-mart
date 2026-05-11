<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f4f4f4; border: 1px solid #ccc; padding: 8px; }
        td { border: 1px solid #ccc; padding: 8px; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 9px; text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN PENJUALAN ALTIK MART</h2>
        <p style="margin:5px 0;">Periode Laporan: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total Jual</th>
                <th class="text-right">Total Laba</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $item)
            <tr>
                <td>{{ $item['sku'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">Rp {{ number_format($item['price_unit'], 0, ',', '.') }}</td>
                <td class="text-right">{{ $item['qty'] }}</td>
                <td class="text-right">Rp {{ number_format($item['total_sales'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item['total_profit'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; float: right; width: 250px;">
        <table style="border: none;">
            <tr>
                <td style="border:none;"><strong>Total Omzet</strong></td>
                <td style="border:none;" class="text-right"><strong>Rp {{ number_format($grandTotalSales, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border:none; color: green;"><strong>Laba Bersih</strong></td>
                <td style="border:none; color: green;" class="text-right"><strong>Rp {{ number_format($grandTotalProfit, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak otomatis pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>