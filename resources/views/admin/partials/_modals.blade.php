<div id="modalTambahBarang" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-[100] backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-[32px] overflow-hidden p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-xl">Tambah Produk Baru</h3>
            <button onclick="closeModal('modalTambahBarang')" class="text-gray-400 hover:text-gray-600 text-2xl transition-colors">&times;</button>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            @if(auth()->user()->role == 'admin')
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Pemilik Produk (Penjual)</label>
                <select name="user_id" required class="w-full p-4 bg-blue-50 rounded-2xl border-none outline-none font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="{{ auth()->id() }}">Saya Sendiri (Admin Koperasi)</option>
                    @foreach(\App\Models\User::where('role', 'seller')->get() as $seller)
                        <option value="{{ $seller->id }}">{{ $seller->name }} (Siswa/Penjual)</option>
                    @endforeach
                </select>
            </div>
            @else
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Kategori</label>
                    <select name="category" id="in_category" onchange="generateSKU()" required class="w-full p-3 bg-gray-100 rounded-xl outline-none border-2 border-transparent focus:border-blue-500 transition-all">
                        <option value="">-- Pilih --</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Kode SKU</label>
                    <input type="text" name="product_code" id="in_sku" readonly class="w-full p-3 bg-gray-50 rounded-xl outline-none font-bold text-blue-600 border-none cursor-default">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nama Produk</label>
                <input type="text" name="name" required class="w-full p-4 bg-gray-100 rounded-2xl border-none outline-none font-bold focus:ring-2 focus:ring-gray-200 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Harga Beli</label>
                    <input type="number" name="purchase_price" required class="w-full p-4 bg-gray-100 rounded-2xl border-none outline-none font-bold focus:ring-2 focus:ring-gray-200 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Harga Jual</label>
                    <input type="number" name="price" required class="w-full p-4 bg-gray-100 rounded-2xl border-none outline-none font-bold focus:ring-2 focus:ring-gray-200 transition-all">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Stok Awal</label>
                <input type="number" name="stock" required class="w-full p-4 bg-gray-100 rounded-2xl border-none outline-none font-bold focus:ring-2 focus:ring-gray-200 transition-all">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Foto Produk</label>
                <input type="file" name="image" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase shadow-lg shadow-blue-200 mt-4 transition-all hover:bg-blue-700 hover:-translate-y-1 active:scale-95">
                Simpan Produk
            </button>
        </form>
    </div>
</div>

<div id="modalEditBarang" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden transform transition-all">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-xl font-black text-gray-800">Edit Produk & Stok</h3>
                <p class="text-xs text-gray-500 font-medium">Perbarui informasi detail barang Anda</p>
            </div>
            <button onclick="closeModal('modalEditBarang')" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times-circle text-2xl"></i>
            </button>
        </div>

        <form id="formEditBarang" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">

            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Barang</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-700 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kategori</label>
                        <input type="text" id="edit_category" name="category" required readonly
                            class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-gray-500 font-bold outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Stok</label>
                        <input type="number" id="edit_stock" name="stock" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-700 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Harga Beli (Modal)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold text-sm">Rp</span>
                            <input type="number" id="edit_purchase_price" name="purchase_price" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-700 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Harga Jual</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-blue-500 font-bold text-sm">Rp</span>
                            <input type="number" id="edit_price" name="price" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-blue-600 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal('modalEditBarang')"
                    class="flex-1 px-6 py-4 rounded-2xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-[2] px-6 py-4 rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalDetailPesanan" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-[110] backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-[32px] p-8 shadow-2xl overflow-y-auto max-h-[90vh]" id="printableInvoice">
        
        <div class="flex justify-between items-center mb-6 no-print">
            <h3 class="font-bold text-gray-800 text-xl">Detail Pesanan</h3>
            <button onclick="closeModal('modalDetailPesanan')" class="text-gray-400 text-2xl hover:text-gray-600 transition-colors">&times;</button>
        </div>

        <div class="hidden print-only mb-8 text-center border-b-2 border-gray-100 pb-4">
            <h2 class="text-2xl font-black uppercase tracking-tighter">Altik Mart</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest">Struk Belanja Resmi - Pesanan Online</p>
        </div>

        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-4 mb-4">
            <div>
                <h2 class="text-xl font-black text-blue-600 uppercase">Invoice</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Nomor Pesanan: <span id="det_order_id" class="text-gray-800">#0</span>
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex justify-between p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <div>
                    <span class="text-blue-800 font-black block text-[9px] uppercase tracking-widest">Status</span>
                    <span id="det_current_status" class="font-black text-blue-600 uppercase text-sm"></span>
                </div>
                <div class="text-right">
                    <span class="text-blue-800 font-black block text-[9px] uppercase tracking-widest">Waktu Order</span>
                    <span id="det_order_date" class="text-[10px] font-bold text-gray-500"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Informasi Penerima</p>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user text-[10px] text-blue-500 w-4"></i>
                            <p id="det_customer_name" class="font-black text-gray-800"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone text-[10px] text-blue-500 w-4"></i>
                            <p id="det_customer_phone" class="font-bold text-gray-600 text-xs"></p>
                        </div>
                        <div class="flex items-start gap-2 mt-2 pt-2 border-t border-gray-200">
                            <i class="fas fa-map-marker-alt text-[10px] text-red-500 w-4 mt-1"></i>
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Alamat Pengiriman:</p>
                                <p id="det_customer_address" class="text-xs text-gray-700 leading-relaxed font-medium"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center px-2">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Metode Kurir</span>
                <span id="det_shipping_method" class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-black text-gray-600 uppercase"></span>
            </div>

            <div class="border-t border-dashed border-gray-200 pt-4">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Rincian Produk</p>
                <div id="det_items_list" class="space-y-3"></div>
            </div>

            <div class="flex justify-between items-center px-2 mt-4 text-gray-500">
                <span class="text-[10px] font-bold uppercase tracking-widest">BIAYA KIRIM</span>
                <span id="det_shipping_cost_display" class="font-black text-xs text-gray-800 italic">Rp 0</span>
            </div>
            
            <div class="flex justify-between p-5 bg-gray-900 rounded-2xl text-white shadow-lg">
                <span class="font-bold uppercase text-xs self-center tracking-widest opacity-70">Total Bayar</span>
                <span id="det_total_final" class="font-black text-2xl tracking-tighter"></span>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 no-print">
            <button onclick="printInvoice()" class="flex-1 bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition flex items-center justify-center gap-2">
                <i class="fas fa-print"></i>
                <span>Cetak Invoice</span>
            </button>
            
            <form id="formUpdateStatus" method="POST" class="border-t pt-6">
                @csrf
                @method('PATCH')
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Perbarui Status Pesanan</label>
                <div class="flex gap-2">
                    <select name="status" id="det_status_select" class="flex-1 p-4 bg-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none border-none focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Dikemas">Dikemas</option>
                        <option value="Dikirim">Dikirim</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Gagal">Gagal</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest transition-all hover:bg-blue-700 shadow-lg shadow-blue-200 active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->role == 'admin' || auth()->user()->role == 'seller')
<div id="modalTambahKategori" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-[100] backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-[32px] overflow-hidden p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-xl">Tambah Kategori</h3>
            <button type="button" onclick="closeModal('modalTambahKategori')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
    @csrf
    
    @if($errors->any())
        <div class="p-3 bg-red-100 text-red-600 text-xs rounded-xl font-bold">
            {{ $errors->first() }}
        </div>
    @endif
            @csrf
            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Nama Kategori Baru</label>
                <input type="text" name="name" required placeholder="Contoh: Alat Tulis" 
                       class="w-full p-4 bg-gray-100 rounded-2xl border-none outline-none font-bold focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modalTambahKategori')" 
                        class="flex-1 py-4 bg-gray-100 text-gray-400 rounded-2xl font-black uppercase text-xs hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        body * { visibility: hidden; }
        #printableInvoice, #printableInvoice * { visibility: visible; }
        #printableInvoice { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
    }
</style>