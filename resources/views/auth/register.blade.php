<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa - KoperasiSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
                <p class="text-gray-500 text-sm mt-2">Gabung dengan komunitas koperasi sekolah</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Sekolah</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ulangi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all mt-4">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk</a></p>
            </div>
        </div>
    </div>

</body>
</html>