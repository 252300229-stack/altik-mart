<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Altik Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .sidebar-active { background-color: #2563eb !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
        .content-section { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        @media print {
            body * { visibility: hidden; }
            #printableInvoice, #printableInvoice * { visibility: visible; }
            #printableInvoice { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

    <aside id="mainSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-white flex-shrink-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col border-r border-gray-800">
       <div class="p-6 text-2xl font-bold text-blue-400 italic tracking-tighter flex justify-between items-center">
    <span>
        Altik<span class="text-white">{{ ucfirst(auth()->user()->role) }}</span>
    </span>
    <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
        <i class="fas fa-times text-xl"></i>
    </button>
</div>
        
        <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
            <button onclick="showSection('section-dashboard')" id="btn-dashboard" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition nav-link sidebar-active text-left text-sm font-semibold">
                <i class="fas fa-home"></i> Dashboard
            </button>
            <p class="text-[10px] text-gray-500 uppercase font-black mt-8 mb-2 px-4 tracking-widest">Menu Utama</p>
            <button onclick="showSection('section-pesanan')" id="btn-pesanan" class="w-full flex items-center gap-3 hover:bg-gray-800 px-4 py-3 rounded-xl text-gray-300 transition text-left nav-link text-sm font-semibold">
                <i class="fas fa-shopping-cart"></i> Pesanan
            </button>
            <button onclick="showSection('section-barang')" id="btn-barang" class="w-full flex items-center gap-3 hover:bg-gray-800 px-4 py-3 rounded-xl text-gray-300 transition text-left nav-link text-sm font-semibold">
                <i class="fas fa-box"></i> Stok Barang
            </button>
            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'seller')
            <button onclick="showSection('section-kategori')" id="btn-kategori" class="w-full flex items-center gap-3 hover:bg-gray-800 px-4 py-3 rounded-xl text-gray-300 transition text-left nav-link text-sm font-semibold">
                <i class="fas fa-tags"></i> Kategori Barang
            </button>
            @endif
            <button onclick="showSection('section-keuangan')" id="btn-keuangan" class="w-full flex items-center gap-3 hover:bg-gray-800 px-4 py-3 rounded-xl text-gray-300 transition text-left nav-link text-sm font-semibold">
                <i class="fas fa-wallet"></i> Keuangan
            </button>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <div class="px-6 py-4 mb-4 border-b border-gray-800/50">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-blue-500/30">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-bold text-white shadow-lg shadow-blue-500/20">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-[#0f172a] rounded-full"></div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="mt-4 flex items-center justify-center gap-2 py-2 px-4 rounded-xl bg-gray-800/50 hover:bg-gray-800 border border-gray-700/50 transition-all group">
                    <i class="fas fa-cog text-[10px] text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-[10px] font-bold text-gray-400 group-hover:text-white uppercase tracking-tighter">Pengaturan Akun</span>
                </a>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-400 flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 w-full rounded-xl transition font-bold text-sm text-left">
                    <i class="fas fa-power-off"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity"></div>

    <main class="flex-1 p-4 md:p-8 max-h-screen overflow-y-auto w-full">
        <div class="md:hidden mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="text-xl font-bold text-blue-600 italic">Altik<span class="text-gray-900">Admin</span></div>
            <button onclick="toggleSidebar()" class="p-2 w-10 h-10 bg-gray-50 rounded-xl text-gray-600 border border-gray-100 flex items-center justify-center">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-xl hover:opacity-50">&times;</button>
            </div>
        @endif


        @include('admin.partials._dashboard_summary')
        @include('admin.partials._pesanan')
        @include('admin.partials._barang')
        @include('admin.partials._kategori')
        
        <section id="section-keuangan" class="content-section hidden">
            <div class="no-print mb-8 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                <input type="hidden" name="tab" id="current-tab-input" value="{{ request('tab', 'dashboard') }}">
                <div class="flex-1 w-full">
                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block ml-2">Dari Tanggal</label>
                    <input type="date" name="start_sales" value="{{ request('start_sales') }}" 
                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex-1 w-full">
                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block ml-2">Sampai Tanggal</label>
                    <input type="date" name="end_sales" value="{{ request('end_sales') }}" 
                        class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    @if(request('start_sales'))
                        <a href="{{ route('admin.dashboard', ['tab' => request('tab')]) }}" class="flex-1 md:flex-none bg-gray-100 text-gray-500 px-6 py-3 rounded-2xl font-bold hover:bg-gray-200 text-center">Reset</a>
                    @endif
                </div>
            </form>
        </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold mb-4">Laporan Penjualan</h3>
                    <p class="text-sm text-gray-500 mb-6">Unduh laporan keuntungan berdasarkan transaksi yang selesai.</p>
                    <a href="{{ route('admin.cetak', ['start_date' => request('start_sales'), 'end_date' => request('end_sales')]) }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                        <i class="fas fa-file-pdf"></i> Cetak PDF Penjualan
                    </a>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold mb-4">Laporan Pembelian Barang</h3>
                    <p class="text-sm text-gray-500 mb-6">Unduh rincian modal dan sisa stok barang yang ada di gudang.</p>
                    <a href="{{ route('admin.cetakPembelian') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                        <i class="fas fa-boxes"></i> Cetak PDF Pembelian
                    </a>
                </div>
            </div>
            @include('admin.partials._keuangan_table')
            @include('admin.partials._keuangan') 
        </section>
    </main>

    @include('admin.partials._modals')

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.add('hidden'));
            const target = document.getElementById(sectionId);
            if(target) target.classList.remove('hidden');
            
            document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('sidebar-active'));
            const suffix = sectionId.split('-')[1];
            const btn = document.getElementById('btn-' + suffix);
            if(btn) btn.classList.add('sidebar-active');

            // Update input hidden untuk form filter agar tab tetap terkunci
            const tabInput = document.getElementById('current-tab-input');
            if(tabInput) tabInput.value = suffix;

            // Simpan status tab di URL tanpa reload
            const url = new URL(window.location);
            url.searchParams.set('tab', suffix);
            window.history.pushState({}, '', url);

            if (window.innerWidth < 768) toggleSidebar();
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if(sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }

        function openModal(id) {
            const m = document.getElementById(id);
            if(m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        
        function closeModal(id) {
            const m = document.getElementById(id);
            if(m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        }

        function openOrderModal(order) {
            if(!order) return;
            document.getElementById('det_order_id').innerText = '#' + (order.order_id || '0');
            document.getElementById('det_current_status').innerText = (order.status || 'MENUNGGU').toUpperCase();
            const date = new Date(order.created_at);
            document.getElementById('det_order_date').innerText = date.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'});
            document.getElementById('det_customer_name').innerText = order.customer_name || '-';
            document.getElementById('det_customer_phone').innerText = order.customer_phone || '-';
            document.getElementById('det_customer_address').innerText = order.address || 'Alamat tidak tersedia';
            document.getElementById('det_shipping_method').innerText = (order.shipping_method || 'AMBIL DI TEMPAT').toUpperCase();

            const list = document.getElementById('det_items_list');
            list.innerHTML = ''; 
            if (order.items) {
                order.items.forEach(item => {
                    const subtotal = item.price * item.quantity;
                    list.innerHTML += `<div class="flex justify-between items-center py-2 border-b border-gray-50"><div class="flex items-center gap-3"><div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold text-xs">${item.quantity}x</div><span class="text-xs font-semibold text-gray-700">${item.product_name}</span></div><span class="text-xs font-bold text-gray-800">Rp ${parseInt(subtotal).toLocaleString('id-ID')}</span></div>`;
                });
            }
            document.getElementById('det_shipping_cost_display').innerText = 'Rp ' + parseInt(order.shipping_cost || 0).toLocaleString('id-ID');
            document.getElementById('det_total_final').innerText = 'Rp ' + parseInt(order.total_price).toLocaleString('id-ID');
            openModal('modalDetailPesanan');
        }

        function bukaModalEdit(id, nama, kategori, beli, jual, stok) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = nama;
            document.getElementById('edit_category').value = kategori;
            document.getElementById('edit_purchase_price').value = beli;
            document.getElementById('edit_price').value = jual;
            document.getElementById('edit_stock').value = stok;
            document.getElementById('formEditBarang').action = "/admin/products/" + id;
            openModal('modalEditBarang');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            const activeTab = params.get('tab');
            if (activeTab) {
                showSection('section-' + activeTab);
            }
        });
        function printInvoice() {
    // Pastikan modal sedang terbuka
    const invoice = document.getElementById('printableInvoice');
    
    if (invoice) {
        // Trigger print browser
        window.print();
    } else {
        alert("Area invoice tidak ditemukan!");
    }
}
    </script>
</body>
</html>