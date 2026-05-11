<div id="section-keuangan" class="content-section hidden space-y-8">
    {{-- Header Laporan --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Laporan <span class="text-blue-600">Keuangan</span></h1>
            <p class="text-gray-500 text-sm font-medium">Detail arus kas dan nilai aset barang saat ini.</p>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Update Terakhir</p>
            <p class="text-xs font-bold text-gray-700">{{ date('d F Y, H:i') }}</p>
        </div>
    </div>
   
    {{-- Card Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-emerald-50 to-white p-7 rounded-[2.5rem] border border-emerald-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-100/50 rounded-full group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 relative z-10">
                Total Omzet ({{ request('start_sales') ? 'Filtered' : 'Semua' }})
            </p>
            <h2 class="text-3xl font-black text-emerald-900 relative z-10">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white p-7 rounded-[2.5rem] border border-blue-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100/50 rounded-full group-hover:scale-110 transition-transform"></div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2 relative z-10">
                Total Laba Bersih ({{ request('start_sales') ? 'Filtered' : 'Semua' }})
            </p>
            <h2 class="text-3xl font-black text-blue-900 relative z-10">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h2>
        </div>
    </div>

    {{-- Form Filter & Cetak Terpadu --}}
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                <i class="fas fa-calendar-alt text-lg"></i>
            </div>
            <div>
                <h3 class="font-black text-gray-800 text-lg">Filter & Cetak Laporan</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Atur rentang waktu untuk analisa data</p>
            </div>
        </div>

        {{-- UPDATE: Form Filter Dashboard --}}
        <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50/80 p-6 rounded-[2rem] border border-gray-100 mb-4">
            <input type="hidden" name="tab" value="keuangan">

            <div class="md:col-span-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-2">Dari Tanggal</label>
                <input type="date" name="start_sales" value="{{ request('start_sales') }}" required 
                    class="w-full px-5 py-3.5 rounded-2xl border-gray-200 text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none bg-white">
            </div>
            <div class="md:col-span-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-2">Sampai Tanggal</label>
                <input type="date" name="end_sales" value="{{ request('end_sales') }}" required 
                    class="w-full px-5 py-3.5 rounded-2xl border-gray-200 text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none bg-white">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-4 rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-3">
                    <i class="fas fa-filter"></i>
                    <span>Terapkan Filter Dashboard</span>
                </button>
            </div>
        </form>

        {{-- UPDATE: Form Khusus Cetak PDF --}}
        <form action="{{ route('admin.cetak') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
             {{-- Kita gunakan parameter start_date & end_date sesuai yang diharapkan cetakLaporan di Controller --}}
             <input type="hidden" name="start_date" value="{{ request('start_sales') }}">
             <input type="hidden" name="end_date" value="{{ request('end_sales') }}">
             
             <div class="md:col-span-12">
                <button type="submit" class="w-full bg-emerald-600 text-white px-6 py-4 rounded-2xl font-black hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-3">
                    <i class="fas fa-file-pdf"></i>
                    <span>Generate Report PDF (Sesuai Filter)</span>
                </button>
            </div>
        </form>
        
        @if(request('start_sales'))
        <div class="mt-4 flex justify-center">
            <a href="{{ route('admin.dashboard', ['tab' => 'keuangan']) }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-blue-600 transition">
                <i class="fas fa-sync-alt mr-1"></i> Reset Filter Tanggal
            </a>
        </div>
        @endif
    </div>

    {{-- Row Visual: Ringkasan Item & Aset --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- List Item Terjual --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <i class="fas fa-receipt text-blue-500 text-xl"></i>
                    <h3 class="font-black text-gray-800 tracking-tight">Ringkasan Item Terjual</h3>
                </div>
            </div>
            
            <div class="space-y-3 max-h-[450px] overflow-y-auto pr-3 custom-scrollbar">
                @php
                    $validOrders = $orders->whereIn('status', ['Selesai', 'Success', 'success', 'selesai', 'paid', 'dikirim']);
                    $itemCount = 0;
                @endphp

                @foreach($validOrders as $order)
                    @foreach($order->items as $item)
                        @if($item->user_id == auth()->id())
                        @php $itemCount += $item->quantity; @endphp
                        <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 flex justify-between items-center group hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:scale-110 transition-transform">
                                    <span class="text-xs font-black text-blue-600">{{ $item->quantity }}x</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800 leading-tight">
                                        {{ $item->product->name ?? $item->product_name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-bold mt-0.5">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900 italic">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                <p class="text-[8px] text-gray-400 uppercase font-black">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endforeach

                @if($itemCount == 0)
                    <div class="py-20 text-center text-gray-400 italic text-sm">Tidak ada item terjual di periode ini.</div>
                @endif
            </div>

            <div class="mt-auto pt-6 border-t border-dashed border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Volume Terjual</span>
                    <span class="text-sm font-black text-gray-700 bg-gray-100 px-4 py-1 rounded-full">{{ $itemCount }} Item</span>
                </div>
            </div>
        </div>

        {{-- List Aset Stok --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <i class="fas fa-box text-orange-500 text-xl"></i>
                    <h3 class="font-black text-gray-800 tracking-tight">Nilai Aset Stok</h3>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Asset</p>
                    <p class="text-lg font-black text-orange-600 italic">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-3 custom-scrollbar">
                @foreach($products as $product)
                <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 flex justify-between items-center hover:bg-white hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center text-[10px] text-gray-400 uppercase">N/A</div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 bg-white px-1.5 py-0.5 rounded-md shadow-sm border border-gray-100">
                                <p class="text-[8px] font-black text-gray-600">{{ $product->stock }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-900 leading-tight">{{ $product->name }}</p>
                            <p class="text-[10px] text-gray-500 font-medium">Modal: Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Value</p>
                        <p class="font-black text-sm text-orange-600">Rp {{ number_format($product->purchase_price * $product->stock, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>