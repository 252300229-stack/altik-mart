<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <span class="text-2xl font-bold text-blue-600">Koperasi<span class="text-gray-800">Siswa</span></span>
                </a>
            </div>

            <div class="hidden md:block flex-1 max-w-md mx-8">
                <div class="relative">
                    <input type="text" 
                           class="w-full bg-gray-100 border-none rounded-full py-2 px-4 focus:ring-2 focus:ring-blue-400 text-sm" 
                           placeholder="Cari buku, seragam, atau snack...">
                    <i class="fas fa-search absolute right-4 top-3 text-gray-400 text-xs"></i>
                </div>
            </div>

            <div class="flex items-center space-x-5">
                <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative transition">
                    <i class="fas fa-shopping-cart"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] rounded-full h-4 w-4 flex items-center justify-center font-bold">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    @if(auth()->user()->role !== 'customer')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <div class="flex items-center gap-3 ml-2 border-l pl-5">
                        <div class="text-right hidden sm:block">
                            <p class="text-[10px] font-bold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[8px] font-black text-blue-500 uppercase tracking-tighter">{{ auth()->user()->role }}</p>
                        </div>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition text-sm flex items-center" title="Keluar">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-blue-600 transition">Masuk</a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white px-5 py-2 rounded-full text-xs font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 btn-primary">
                       Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    .btn-primary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
    }
    /* Pastikan header dashboard tidak tertutup navbar jika menggunakan sticky */
</style>