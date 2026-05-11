<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altik Mart | Katalog Produk</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>

    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="text-xl font-bold text-blue-600">Altik<span class="text-gray-800">Mart</span></a>
            
            <div class="hidden md:block flex-1 max-w-md mx-8">
                <input type="text" class="w-full bg-gray-100 border-none rounded-full py-2 px-4 focus:ring-2 focus:ring-blue-400" placeholder="Cari barang...">
            </div>

            <div class="flex items-center gap-6 text-gray-600">
                <a href="{{ route('my.orders') }}" class="hidden sm:flex items-center gap-2 text-sm font-bold hover:text-blue-600 transition">
                    <i class="fas fa-receipt text-lg"></i>
                    <span>Pesanan Saya</span>
                </a>

                <a href="/cart" class="relative group">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">
                        {{ count((array) session('cart')) }}
                    </span>
                </a>

                <div class="flex items-center gap-2 border-l pl-4">
                    @auth
                        <div class="text-right leading-tight hidden sm:block">
                            <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] uppercase text-blue-500 font-bold tracking-tighter">{{ Auth::user()->role }}</p>
                        </div>
                        <div class="relative group">
                            <button class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-black shadow-lg shadow-blue-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 hidden group-hover:block z-50 overflow-hidden">
                                <div class="px-4 py-2 border-b border-gray-50 md:hidden">
                                    <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-blue-500 uppercase">{{ Auth::user()->role }}</p>
                                </div>
                                
                                <a href="{{ route('my.orders') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition sm:hidden">
                                    <i class="fas fa-receipt mr-2 text-blue-500"></i> Pesanan Saya
                                </a>

                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-blue-600 font-bold hover:bg-blue-50 transition">
                                        <i class="fas fa-th-large mr-2"></i> Admin Dashboard
                                    </a>
                                @endif
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition font-bold">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-blue-600 hover:underline">Login</a>
                            <a href="{{ route('register') }}" class="text-sm font-bold text-gray-500 hover:text-gray-900 transition">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 animate-bounce">
            <div class="bg-green-500 border-none text-white px-4 py-3 rounded-2xl flex items-center gap-3 shadow-lg shadow-green-100">
                <i class="fas fa-check-circle text-xl"></i> 
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-400 rounded-[2.5rem] p-8 md:p-12 mb-10 text-white shadow-2xl shadow-blue-200 relative overflow-hidden">
            <div class="relative z-10 md:max-w-xl">
                <span class="text-[10px] font-black bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full uppercase tracking-[0.2em]">Koperasi Digital</span>
                <h1 class="text-4xl md:text-5xl font-black mt-6 leading-tight">Belanja Kebutuhan <br> Jadi Lebih <span class="text-blue-200 italic">Simpel.</span></h1>
                <p class="mt-4 text-blue-500 bg-white/90 inline-block px-4 py-1 rounded-lg font-bold text-sm shadow-sm">DISKON KHUSUS ANGGOTA HARI INI!</p>
                <div class="mt-8 flex gap-3">
                    <button class="bg-white text-blue-600 px-8 py-3 rounded-2xl font-black text-sm uppercase tracking-wider shadow-lg hover:bg-gray-900 hover:text-white transition transform active:scale-95">Mulai Belanja</button>
                    <a href="{{ route('my.orders') }}" class="bg-blue-800/30 backdrop-blur-md text-white border border-white/20 px-6 py-3 rounded-2xl font-bold text-sm hover:bg-white/10 transition">Cek Pesanan</a>
                </div>
            </div>
            <i class="fas fa-shopping-basket absolute -right-16 -bottom-16 text-[22rem] text-white/10 rotate-12 pointer-events-none"></i>
        </div>

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                Katalog Produk
            </h2>
            <div class="flex gap-2">
                <button class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-blue-600 transition shadow-sm"><i class="fas fa-th-large"></i></button>
                <button class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-blue-600 transition shadow-sm"><i class="fas fa-list"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            @forelse($products as $p)
            <div class="bg-white rounded-[2rem] p-5 shadow-xl shadow-gray-200/50 border border-transparent hover:border-blue-100 hover:shadow-blue-900/5 transition-all duration-500 group flex flex-col">
                <div class="bg-gray-50 h-48 rounded-[1.5rem] mb-5 flex items-center justify-center overflow-hidden relative shadow-inner">
                    @if($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    @else
                        <div class="flex flex-col items-center opacity-20">
                            <i class="fas fa-box text-5xl mb-2"></i>
                            <span class="text-[10px] font-bold">NO IMAGE</span>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="text-[10px] font-black text-white bg-blue-600 px-3 py-1 rounded-full uppercase tracking-tighter shadow-lg">
                            {{ $p->category }}
                        </span>
                    </div>
                </div>

                <div class="flex-grow">
                    <h3 class="font-bold text-gray-800 text-lg line-clamp-2 leading-snug group-hover:text-blue-600 transition">{{ $p->name }}</h3>
                    <div class="flex items-center justify-between mt-3">
                        <p class="text-blue-600 font-black text-xl tracking-tighter">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex text-yellow-400 text-[10px]">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stok: {{ $p->stock }}</p>
                    </div>
                </div>

                <form action="/cart/add/{{ $p->id }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full mt-6 bg-gray-900 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-200 transition-all duration-300 transform active:scale-95">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
            @empty
            <div class="col-span-full py-24 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-ghost text-4xl text-gray-200"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Ups! Produk Kosong</h3>
                <p class="text-gray-400 mt-2">Katalog sedang diperbarui oleh admin koperasi.</p>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-white border-t mt-20">
        <div class="max-w-7xl mx-auto px-4 py-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <a href="/" class="text-2xl font-black text-blue-600 italic">Altik<span class="text-gray-900">Mart.</span></a>
                <p class="text-gray-400 text-sm mt-2 font-medium">Solusi Belanja Koperasi Digital Masa Kini</p>
            </div>
            <div class="flex gap-6 text-gray-400 text-sm font-bold">
                <a href="#" class="hover:text-blue-600">Bantuan</a>
                <a href="{{ route('my.orders') }}" class="hover:text-blue-600">Lacak Pesanan</a>
                <a href="#" class="hover:text-blue-600">Ketentuan</a>
            </div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">&copy; 2026 Altik Mart Team</p>
        </div>
    </footer>

</body>
</html>