<div id="section-dashboard" class="content-section space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Ringkasan <span class="text-blue-600">Bisnis</span></h1>
            <p class="text-gray-500 text-sm font-medium">Pantau performa penjualan Altik Mart hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Penjualan</span>
            </div>
            <h2 class="text-3xl font-black text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
            <p class="text-[10px] text-green-600 font-bold mt-2"><i class="fas fa-arrow-up mr-1"></i> Omzet Masuk</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-blue-900/5 border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                    <i class="fas fa-box-open fa-lg"></i>
                </div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Nilai Stok (Aset)</span>
            </div>
            <h2 class="text-3xl font-black text-gray-900">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</h2>
            <p class="text-[10px] text-gray-500 font-bold mt-2">Modal Tertanam di Barang</p>
        </div>

        <div class="bg-blue-600 p-6 rounded-[2rem] shadow-xl shadow-blue-900/20 text-white border border-blue-500">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <span class="text-xs font-black text-white/70 uppercase tracking-widest">Laba Bersih</span>
            </div>
            <h2 class="text-3xl font-black text-white">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h2>
            <p class="text-[10px] text-blue-100 font-bold mt-2">Keuntungan dari Barang Terjual</p>
        </div>
    </div>
</div>