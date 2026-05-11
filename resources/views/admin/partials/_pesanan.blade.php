<section id="section-pesanan" class="content-section hidden">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Riwayat Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 text-[10px] uppercase font-black text-gray-500 tracking-widest">
                    <tr>
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Item Produk</th>
                        <th class="px-6 py-4">Total Saya</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-blue-600">#{{ $order->order_id }}</span>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                {{-- FILTER ITEM: Hanya tampilkan milik seller yang sedang login --}}
                                @foreach($order->items as $item)
                                    @if(auth()->user()->role == 'admin' || $item->user_id == auth()->id())
                                        <div class="flex justify-between gap-4 text-xs">
                                            <span class="text-gray-600">{{ $item->product_name }}</span>
                                            <span class="font-bold text-gray-900">x{{ $item->quantity }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{-- HITUNG TOTAL: Hanya akumulasi harga barang milik seller ini --}}
                            @php
                                $myTotal = 0;
                                foreach($order->items as $item) {
                                    if(auth()->user()->role == 'admin' || $item->user_id == auth()->id()) {
                                        $myTotal += ($item->price * $item->quantity);
                                    }
                                }
                                // Tambahkan ongkir hanya untuk admin agar tidak membingungkan seller 
                                // (Atau sesuaikan jika seller juga menanggung ongkir)
                                if(auth()->user()->role == 'admin') {
                                    $myTotal += $order->shipping_cost;
                                }
                            @endphp
                            <span class="text-sm font-black text-gray-800">Rp {{ number_format($myTotal) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase 
                                {{ $order->status == 'Success' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick='openOrderModal(@json($order))' class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>