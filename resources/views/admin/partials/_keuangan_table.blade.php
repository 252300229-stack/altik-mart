<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-8">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Ringkasan Penjualan Terakhir</h3>
        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full uppercase tracking-tighter">Hanya Pesanan Selesai</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Harga Saya</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{-- Gunakan filter status yang sesuai dengan database (biasanya diawali huruf kapital) --}}
                @forelse($orders->whereIn('status', ['Selesai', 'Paid', 'Success', 'Dikirim', 'success', 'selesai'])->take(10) as $order)
                <tr class="hover:bg-gray-50/30 transition">
                    <td class="px-6 py-4 text-sm font-bold text-gray-700">#{{ $order->order_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm font-black text-gray-900">
                        @php
                            // HITUNG ULANG: Hanya total barang milik user yang login
                            $myIncome = 0;
                            foreach($order->items as $item) {
                                if($item->user_id == auth()->id()) {
                                    $myIncome += ($item->price * $item->quantity);
                                }
                            }
                        @endphp
                        Rp {{ number_format($myIncome, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-lg">
                            {{ $order->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i class="fas fa-receipt fa-2xl mb-2 opacity-20"></i>
                            <p>Belum ada transaksi yang diselesaikan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>