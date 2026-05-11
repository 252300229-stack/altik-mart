<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Altik Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        /* Animasi loading sederhana */
        .loading { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body class="bg-[#f8fafc] p-4 md:p-12">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <a href="/" class="text-blue-600 font-bold flex items-center gap-2 mb-2 hover:gap-3 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali Belanja
                </a>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter">Keranjang <span class="text-blue-600">Kamu</span></h2>
            </div>
            <i class="fas fa-shopping-bag text-5xl text-gray-100"></i>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100 overflow-hidden">
            <div class="p-8 overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead>
                        <tr class="text-left text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-6">Produk</th>
                            <th class="pb-6 text-center">Harga</th>
                            <th class="pb-6 text-center">Jumlah</th>
                            <th class="pb-6 text-right">Subtotal</th>
                            <th class="pb-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php $total = 0 @endphp
                        @forelse((array) session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <tr class="group cart-item-row" data-id="{{ $id }}" data-price="{{ $details['price'] }}">
                                <td class="py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center overflow-hidden">
                                            @if(isset($details['image']))
                                                <img src="{{ asset('storage/' . $details['image']) }}" class="object-cover w-full h-full">
                                            @else
                                                <i class="fas fa-image text-gray-200"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 leading-tight">{{ $details['name'] }}</p>
                                            <p class="text-[10px] text-blue-500 font-bold uppercase mt-1">Tersedia</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    <span class="text-sm font-semibold text-gray-600">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                                </td>
                                <td class="py-6 text-center">
                                    <input type="number" value="{{ $details['quantity'] }}" min="1" 
                                        class="w-16 p-2 bg-gray-50 border-none rounded-xl text-center font-bold focus:ring-2 focus:ring-blue-400 update-cart" 
                                        data-id="{{ $id }}">
                                </td>
                                <td class="py-6 text-right">
                                    <span class="font-black text-gray-900 row-subtotal">
                                        Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-6 text-right pl-4">
                                    <button class="text-gray-300 hover:text-red-500 transition remove-from-cart" data-id="{{ $id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-cart text-6xl text-gray-100 mb-4"></i>
                                        <p class="text-gray-400 italic">Wah, keranjangmu masih kosong nih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50/50 p-8 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pembayaran</p>
                        <h3 class="text-4xl font-black text-gray-900 leading-none">
                            <span class="text-blue-600 text-lg">Rp</span> 
                            <span id="grand-total">{{ number_format($total, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        @if(session('cart'))
                            <a href="{{ route('checkout') }}" class="flex-1 md:flex-none text-center bg-blue-600 text-white px-12 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-600/20 hover:bg-gray-900 hover:shadow-none transition-all duration-300">
                                Checkout Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Logic Update Cart - SEKARANG MENGGUNAKAN RELOAD UNTUK KEAMANAN SESSION
        $(".update-cart").on('change', function (e) {
            let ele = $(this);
            let id = ele.attr("data-id");
            let quantity = ele.val();
            
            // Beri efek loading agar user tidak klik checkout saat proses simpan
            $('body').addClass('loading');

            if(quantity < 1) {
                quantity = 1;
                ele.val(1);
            }

            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id, 
                    quantity: quantity
                },
                success: function (response) {
                    // Paksa reload agar data di server (Checkout) PASTI SAMA dengan tampilan
                    window.location.reload();
                },
                error: function() {
                    alert('Gagal mengupdate jumlah. Stok mungkin tidak cukup.');
                    window.location.reload();
                }
            });
        });

        // Remove from Cart
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            if(confirm("Hapus item ini dari keranjang?")) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: ele.attr("data-id")
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
</body>
</html>