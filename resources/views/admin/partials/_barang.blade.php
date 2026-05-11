<div id="section-barang" class="content-section hidden">
    <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Stok <span class="text-blue-600">Barang</span></h2>
            <p class="text-sm text-gray-500 font-medium">Kelola inventaris dan pantau ketersediaan produk Anda.</p>
        </div>
        <button onclick="openModal('modalTambahBarang')" class="bg-blue-600 text-white px-7 py-3.5 rounded-[20px] font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 active:scale-95 flex items-center gap-2 text-sm">
            <i class="fas fa-plus-circle text-lg"></i> Tambah Produk Baru
        </button>
    </header>

    <section class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-black tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Kode SKU</th>
                        <th class="px-6 py-5">Kode SKU</th>
                        <th class="px-6 py-5 hidden md:table-cell">Kategori</th>
                        <th class="px-6 py-5">Penjual</th>
                        <th class="px-6 py-5 text-right">Harga (Modal/Jual)</th>
                        <th class="px-6 py-5 text-center">Stok</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products ?? [] as $product)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-100 shadow-sm">
                                    @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                        <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-box text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-gray-800 leading-tight">{{ $product->name }}</span>
                                    <span class="block text-[10px] text-gray-400 font-medium md:hidden uppercase mt-1">{{ $product->category }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($product->product_code)
                                <code class="text-[11px] font-mono font-black text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">
                                    {{ $product->product_code }}
                                </code>
                            @else
                                <span class="text-[10px] font-black text-red-400 bg-red-50 px-2.5 py-1 rounded-full uppercase tracking-tighter">No SKU</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 hidden md:table-cell">
                            <span class="text-[10px] font-black text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full uppercase tracking-widest">
                                {{ $product->category }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-800">{{ $product->user->name ?? 'Admin' }}</span>
                                <span class="text-[9px] text-blue-500 uppercase font-black tracking-tighter">{{ $product->user->role ?? 'Staff' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-bold text-gray-400 line-through decoration-red-300">Rp{{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</span>
                                <span class="text-sm font-black text-blue-600">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $isLow = $product->stock < 5;
                            @endphp
                            <div class="inline-flex flex-col items-center">
                                <span class="px-3 py-1 rounded-xl text-xs font-black {{ $isLow ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-emerald-100 text-emerald-600' }}">
                                    {{ $product->stock }}
                                </span>
                                @if($isLow)
                                    <span class="text-[8px] font-black text-red-400 uppercase mt-1 tracking-tighter">Restock!</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="bukaModalEdit('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->category }}', '{{ $product->purchase_price }}', '{{ $product->price }}', '{{ $product->stock }}')" 
                                        class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-amber-200">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center shadow-sm hover:shadow-red-200">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-200">
                                    <i class="fas fa-box-open text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Belum ada barang</h3>
                                <p class="text-sm text-gray-400">Silakan tambah produk baru untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>