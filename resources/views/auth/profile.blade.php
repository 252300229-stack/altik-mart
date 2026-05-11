<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil | Altik Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white rounded-[40px] shadow-2xl shadow-blue-100/50 border border-gray-100 overflow-hidden">
        <div class="bg-[#0f172a] p-8 text-center relative">
            <a href="{{ route('admin.dashboard') }}" class="absolute left-6 top-6 text-gray-400 hover:text-white transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-white font-black text-xl tracking-tight">Pengaturan Akun</h2>
            <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] mt-1">Update Informasi Profil</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col items-center">
                <div class="relative group">
                    <div class="w-28 h-28 rounded-[32px] bg-blue-600 flex items-center justify-center overflow-hidden border-4 border-white shadow-xl">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <label for="image-upload" class="absolute -bottom-2 -right-2 bg-white w-10 h-10 rounded-2xl shadow-lg border border-gray-100 flex items-center justify-center text-blue-600 cursor-pointer hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fas fa-camera text-sm"></i>
                    </label>
                    <input id="image-upload" type="file" name="image" class="hidden">
                </div>
                <p class="text-[9px] text-gray-400 font-bold uppercase mt-4 tracking-widest text-center">Klik ikon kamera untuk ganti foto</p>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required 
                           class="w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none font-bold text-gray-700 transition-all">
                </div>
            </div>

            <div class="space-y-2 opacity-60">
                <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Email Akun</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    <input type="email" value="{{ auth()->user()->email }}" disabled 
                           class="w-full pl-12 pr-6 py-4 bg-gray-100 border-none rounded-2xl font-bold text-gray-500 cursor-not-allowed">
                </div>
            </div>

            <div class="pt-4 space-y-3">
                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all active:scale-95">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="block text-center w-full py-4 bg-gray-50 text-gray-400 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-100 transition-all">
                    Batal & Kembali
                </a>
            </div>
        </form>
    </div>

</body>
</html>