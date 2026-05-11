<form action="{{ route('admin.report') }}" method="GET" class="mb-5">
    <input type="date" name="start_date" value="{{ $startDate }}">
    <input type="date" name="end_date" value="{{ $endDate }}">
    <button type="submit">Filter Laporan</button>
</form>

<div class="grid grid-cols-2 gap-4">
    <div class="card">
        <h3>Laporan Penjualan (Uang Masuk)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>ALTIK-{{ $sale->id }}</td>
                    <td>{{ $sale->name }}</td>
                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <strong>Total Omzet: Rp {{ number_format($totalSales, 0, ',', '.') }}</strong>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="card">
        <h3>Laporan Produk Terjual (Keluar Stok)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produk Terhapus' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>