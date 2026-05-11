<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Belanja | Altik Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 pb-12">
    <nav class="bg-white shadow-sm p-4 mb-6 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="/" class="text-sm font-bold text-blue-600 flex items-center gap-2 hover:bg-blue-50 px-3 py-2 rounded-xl transition">
                <i class="fas fa-arrow-left"></i> Kembali Belanja
            </a>
            <h1 class="font-black text-gray-800 uppercase tracking-widest text-lg">Altik Mart</h1>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto p-4">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Riwayat <span class="text-blue-600">Pesanan Saya</span></h2>
            @if(Auth::check())
                <p class="text-gray-500 mt-2 flex items-center gap-2 text-sm">
                    <i class="fas fa-user-circle text-blue-500"></i>
                    Menampilkan pesanan milik <strong>{{ Auth::user()->name }}</strong>
                </p>
            @endif
        </div>

        @if(!Auth::check())
        <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-gray-100 mb-8">
            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-3 ml-1">Lacak Pesanan Via No. HP</label>
            <form action="{{ route('my.orders') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="phone" value="{{ request('phone') }}" 
                       placeholder="Masukkan No. WhatsApp saat checkout..." 
                       class="flex-1 bg-gray-50 border-none p-4 rounded-2xl focus:ring-2 focus:ring-blue-400 outline-none font-medium">
                <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform active:scale-95">
                    Cari Pesanan
                </button>
            </form>
        </div>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden transition hover:shadow-blue-900/5">
                
                <div class="p-6 bg-gray-50/50 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em]">ID Transaksi</span>
                        <p class="font-mono text-sm text-gray-800 font-bold uppercase">{{ $order->order_id }}</p>
                    </div>
                    <div class="flex items-center gap-4 text-right">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Waktu Pesan</p>
                            <p class="text-xs text-gray-600 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @php
                            $statusStyle = [
                                'pending' => 'bg-amber-100 text-amber-600 border-amber-200',
                                'dikemas' => 'bg-blue-100 text-blue-600 border-blue-200',
                                'dikirim' => 'bg-purple-100 text-purple-600 border-purple-200',
                                'selesai' => 'bg-green-100 text-green-600 border-green-200',
                                'batal'   => 'bg-red-100 text-red-600 border-red-200',
                            ][$order->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase border shadow-sm {{ $statusStyle }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="fas fa-shopping-bag"></i> Rincian Barang
                    </h4>
                    
                    <div class="space-y-5">
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 font-black text-xs group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-400 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }} / item</p>
                                </div>
                            </div>
                            <p class="font-black text-gray-700 italic">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-8 border-t border-dashed border-gray-200 flex flex-col md:flex-row justify-between items-end md:items-center gap-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 shrink-0">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tujuan Pengiriman</p>
                                <p class="text-xs text-gray-600 font-medium leading-relaxed max-w-xs">{{ $order->address }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Bayar</p>
                            <p class="text-3xl font-black text-blue-600 tracking-tighter">
                                <span class="text-sm font-bold italic mr-1">Rp</span>{{ number_format($order->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($order->status == 'pending')
                <div class="px-8 pb-8 flex justify-end">
                    <button class="text-xs font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 flex items-center gap-2">
                        <i class="fas fa-external-link-alt"></i> Lanjut Pembayaran
                    </button>
                </div>
                @endif
            </div>
            @empty
                <div class="text-center py-32 bg-white rounded-[3rem] border border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-receipt text-4xl text-gray-200"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Belum Ada Riwayat</h3>
                    <p class="text-gray-400 mt-2 text-sm">Pesanan yang kamu buat akan muncul di sini secara otomatis.</p>
                    <a href="/" class="inline-block mt-8 bg-gray-900 text-white px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition">Mulai Belanja</a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Tambahkan logika JS di sini jika ingin ada interaksi pop-up detail
    </script>
</body>
</html>