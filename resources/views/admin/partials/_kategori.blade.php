<div id="section-kategori" class="content-section hidden">
    <header class="mb-8 flex justify-between items-center">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Kategori Barang</h2>
        
        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'seller')
        <button onclick="openModal('modalTambahKategori')" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
            <i class="fas fa-plus mr-2"></i> Kategori Baru
        </button>
        @endif
    </header>

    <section class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black border-b">
                <tr>
                    <th class="px-6 py-5">Nama Kategori</th>
                    @if(auth()->user()->role == 'admin')
                    <th class="px-6 py-5 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories ?? [] as $category)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $category->name }}</td>
                    @if(auth()->user()->role == 'admin')
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td class="px-6 py-10 text-center text-gray-400 font-medium">Belum ada kategori tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>