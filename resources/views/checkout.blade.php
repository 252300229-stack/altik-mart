<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengiriman | Altik Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .method-card { transition: all 0.3s ease; border: 2px solid #f1f5f9; }
        .method-card.active { border-color: #2563eb; background-color: #eff6ff; transform: scale(1.02); }
    </style>
</head>
<body class="p-4 md:p-12">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-4 mb-10">
            <a href="{{ route('cart') }}" class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 transition">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </a>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Informasi <span class="text-blue-600">Pengiriman</span></h2>
        </div>

        <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf
            
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100 space-y-5">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Contoh: Aji Purnomo" required 
                               class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                        <input type="number" name="phone" placeholder="0812..." required 
                               class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Alamat Lengkap / Kelas</label>
                        <textarea name="address" placeholder="Jl. Pelita II No. 10..." required rows="3"
                                  class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100">
                    <h3 class="font-bold text-gray-700 uppercase text-xs tracking-widest mb-6 italic">Pilih Metode Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div onclick="setShipping('Ambil Sendiri', 0)" id="btn-Ambil-Sendiri" class="method-card p-4 rounded-3xl cursor-pointer bg-white">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-3 text-green-600">
                                <i class="fas fa-store"></i>
                            </div>
                            <p class="font-bold text-sm text-gray-900">Ambil Sendiri</p>
                            <p class="text-[10px] text-green-600 font-bold uppercase mt-1">Gratis</p>
                        </div>

                        <div onclick="setShipping('JNE Regular', 15000)" id="btn-JNE-Regular" class="method-card p-4 rounded-3xl cursor-pointer bg-white">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-3 text-blue-600">
                                <i class="fas fa-truck"></i>
                            </div>
                            <p class="font-bold text-sm text-gray-900">JNE REG</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Rp 15.000</p>
                        </div>

                        <div onclick="setShipping('GoSend', 20000)" id="btn-GoSend" class="method-card p-4 rounded-3xl cursor-pointer bg-white">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mb-3 text-red-600">
                                <i class="fas fa-motorcycle"></i>
                            </div>
                            <p class="font-bold text-sm text-gray-900">GoSend</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Rp 20.000</p>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_method" id="inp-method" required>
                    <input type="hidden" name="shipping_cost" id="inp-cost" value="0">
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-blue-600 p-8 rounded-[3rem] shadow-2xl shadow-blue-500/40 text-white sticky top-12">
                    <div class="flex items-center gap-3 mb-8">
                        <i class="fas fa-shopping-cart opacity-50"></i>
                        <h3 class="text-xl font-black italic uppercase tracking-tighter">Ringkasan Pesanan</h3>
                    </div>
                    
                    <div class="space-y-4 mb-8 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @php $subtotal = 0; @endphp
                        @foreach((array) session('cart') as $id => $details)
                            @php $subtotal += $details['price'] * $details['quantity'] @endphp
                            <div class="flex justify-between items-center bg-blue-700/30 p-4 rounded-2xl border border-blue-400/20">
                                <div>
                                    <p class="font-bold text-sm">{{ $details['name'] }}</p>
                                    <p class="text-[10px] opacity-70">{{ $details['quantity'] }} unit x Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                </div>
                                <p class="font-black text-sm">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 border-t border-blue-400/30 pt-6">
                        <div class="flex justify-between text-sm opacity-80">
                            <span>Ongkos Kirim</span>
                            <span id="txt-ongkir">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="font-bold text-lg uppercase tracking-tighter">Total Bayar</span>
                            <div class="text-right">
                                <span id="txt-total" class="text-4xl font-black leading-none block">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="pay-button" class="w-full mt-8 bg-white text-blue-600 py-5 rounded-[2rem] font-black uppercase tracking-widest hover:bg-gray-100 transition-all transform hover:scale-[1.02] active:scale-95 shadow-xl">
                        Konfirmasi Pesanan
                    </button>
                    
                    <p class="text-[9px] text-center mt-4 opacity-60 uppercase font-bold tracking-widest">Klik tombol di atas untuk beralih ke pembayaran</p>
                </div>
            </div>
        </form>
    </div>

    <script>
        const subtotal = {{ $subtotal }};

        function setShipping(method, cost) {
            // Update Hidden Input
            document.getElementById('inp-method').value = method;
            document.getElementById('inp-cost').value = cost;

            // Update UI Text
            document.getElementById('txt-ongkir').innerText = 'Rp ' + cost.toLocaleString('id-ID');
            document.getElementById('txt-total').innerText = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');

            // Toggle Visual Class
            document.querySelectorAll('.method-card').forEach(el => el.classList.remove('active'));
            // Ganti spasi dengan strip untuk ID selector
            const safeId = 'btn-' + method.replace(/\s+/g, '-');
            const targetBtn = document.getElementById(safeId);
            if(targetBtn) targetBtn.classList.add('active');
        }

        // Logic Midtrans Snap
        @if(isset($snapToken))
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) { window.location.href = "/my-orders"; },
                onPending: function(result) { window.location.href = "/my-orders"; },
                onError: function(result) { alert("Pembayaran gagal, silakan coba lagi."); }
            });
        @endif
    </script>
</body>
</html>