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
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        
        /* Animasi halus untuk dropdown */
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-dropdown { animation: fadeInScale 0.2s ease-out forwards; }
    </style>
</head>
<body>

    <nav class="bg-white shadow-sm border-b sticky top-0 z-[100]">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="text-xl font-bold text-blue-600 tracking-tighter">Altik<span class="text-gray-800">Mart</span></a>
            
            {{-- Form Pencarian Dinamis --}}
            <form action="{{ route('home') }}" method="GET" class="hidden md:block flex-1 max-w-md mx-8">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-3 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full bg-gray-100 border-none rounded-full py-2.5 pl-11 pr-4 focus:ring-2 focus:ring-blue-400 text-sm" 
                        placeholder="Cari barang di koperasi...">
                </div>
            </form>

            <div class="flex items-center gap-4 md:gap-6 text-gray-600">
                @auth
                    <a href="{{ route('my.orders') }}" class="flex items-center gap-2 text-sm font-bold hover:text-blue-600 transition group">
                        <i class="fas fa-receipt text-lg group-hover:scale-110 transition"></i>
                        <span class="hidden sm:block">Pesanan Saya</span>
                    </a>

                    <a href="{{ route('cart') }}" class="relative group p-2">
                        <i class="fas fa-shopping-cart text-xl group-hover:text-blue-600 transition"></i>
                        @if(count((array) session('cart')) > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full font-bold border-2 border-white animate-pulse">
                                {{ count((array) session('cart')) }}
                            </span>
                        @endif
                    </a>
                @endauth

                <div class="flex items-center gap-2 border-l pl-4">
                    @auth
                        {{-- Info Nama (Desktop Only) --}}
                        <div class="text-right leading-tight hidden sm:block">
                            <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] uppercase text-blue-500 font-black tracking-tighter">{{ Auth::user()->role }}</p>
                        </div>

                        {{-- Dropdown Profil dengan Invisible Bridge --}}
                        <div class="relative group py-2">
                            {{-- Bridge: Area transparan agar hover tidak terputus --}}
                            <div class="absolute -bottom-6 right-0 w-32 h-8"></div>

                            <button class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-black shadow-lg shadow-blue-200 group-hover:rotate-12 transition relative z-10">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>

                            {{-- Menu Dropdown --}}
                            <div class="absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 py-2 hidden group-hover:block z-[110] overflow-hidden transform origin-top-right animate-dropdown">
                                
                                <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50 mb-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Login Sebagai</p>
                                    <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <a href="{{ route('my.orders') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition">
                                    <i class="fas fa-history mr-2 text-blue-500 w-4"></i> Riwayat Belanja
                                </a>

                                {{-- Logic Role Dashboard --}}
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-blue-600 font-black hover:bg-blue-50 transition border-t border-gray-50">
                                        <i class="fas fa-th-large mr-2"></i> Altik<span class="text-blue-400">Admin</span>
                                    </a>
                                @elseif(Auth::user()->role == 'seller')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-emerald-600 font-black hover:bg-emerald-50 transition border-t border-gray-50">
                                        <i class="fas fa-store mr-2"></i> Altik<span class="text-emerald-400">Seller</span>
                                    </a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition font-bold border-t border-gray-50">
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

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-green-500 text-white px-6 py-4 rounded-2xl flex items-center justify-between shadow-xl shadow-green-100 animate-dropdown">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i> 
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/50 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-8">
        {{-- Banner / Hero Section --}}
        <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-400 rounded-[2.5rem] p-8 md:p-16 mb-12 text-white shadow-2xl shadow-blue-200 relative overflow-hidden">
            <div class="relative z-10 md:max-w-2xl">
                <span class="text-[10px] font-black bg-white/20 backdrop-blur-md px-4 py-2 rounded-full uppercase tracking-[0.25em]">Altik Mart Digital</span>
                <h1 class="text-4xl md:text-6xl font-black mt-8 leading-[1.1]">Belanja Kebutuhan <br> Jauh Lebih <span class="text-blue-200 italic underline decoration-blue-300">Mudah.</span></h1>
                <p class="mt-6 text-blue-100 font-medium text-lg opacity-90">Nikmati kemudahan transaksi koperasi sekolah dalam genggaman. Cepat, aman, dan terpercaya.</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="#katalog" class="bg-white text-blue-600 px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition transform active:scale-95">Lihat Katalog</a>
                    @auth
                        <a href="{{ route('my.orders') }}" class="bg-blue-800/30 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-2xl font-bold text-sm hover:bg-white/10 transition">Cek Status Pesanan</a>
                    @endauth
                </div>
            </div>
            <i class="fas fa-shopping-bag absolute -right-10 -bottom-10 text-[25rem] text-white/10 rotate-12 pointer-events-none"></i>
        </div>

        {{-- Judul Katalog --}}
        <div id="katalog" class="flex items-center justify-between mb-10 pt-4">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-4">
                <span class="w-2.5 h-10 bg-blue-600 rounded-full"></span>
                Katalog Produk
            </h2>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
            @forelse($products as $p)
            <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-gray-200/40 border border-transparent hover:border-blue-200 hover:shadow-blue-900/5 transition-all duration-500 group flex flex-col h-full">
                <div class="bg-gray-50 h-52 rounded-[2rem] mb-6 flex items-center justify-center overflow-hidden relative group-hover:shadow-inner transition">
                    @if($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                    @else
                        <div class="flex flex-col items-center text-gray-300">
                            <i class="fas fa-image text-5xl mb-2"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">No Image</span>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="text-[10px] font-black text-white bg-blue-600 px-3.5 py-1.5 rounded-full uppercase tracking-tighter shadow-lg">
                            {{ $p->category->name ?? 'Produk' }}
                        </span>
                    </div>
                </div>

                <div class="flex-grow flex flex-col">
                    <h3 class="font-bold text-gray-800 text-lg line-clamp-2 leading-tight group-hover:text-blue-600 transition mb-2">{{ $p->name }}</h3>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-blue-600 font-black text-2xl tracking-tighter italic">Rp</span>
                        <span class="text-blue-600 font-black text-2xl tracking-tighter">{{ number_format($p->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                @auth
                    <form action="{{ route('cart.add', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full mt-6 bg-gray-900 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-200 transition-all duration-300 transform active:scale-95 group-hover:-translate-y-1">
                            Tambah Ke Keranjang
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full mt-6 bg-blue-50 text-blue-600 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] text-center hover:bg-blue-600 hover:text-white transition-all duration-300 group-hover:-translate-y-1">
                        Login untuk Beli
                    </a>
                @endauth
            </div>
            @empty
            <div class="col-span-full py-32 text-center">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <i class="fas fa-store-slash text-5xl text-gray-200"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Katalog Sedang Kosong</h3>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-white border-t mt-32">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.4em]">&copy; 2026 ALTIK MART ECOSYSTEM</p>
        </div>
    </footer>

</body>
</html>