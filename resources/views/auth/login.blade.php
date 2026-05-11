<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Altik-Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100">
        <div class="p-8 md:p-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg shadow-blue-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
                <p class="text-gray-500 text-sm mt-2">Masuk ke akun Alitk-Mart</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                        placeholder="Masukkan Email">
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-semibold text-gray-700">Password</label>
                        <a href="#" class="text-xs text-blue-600 hover:underline">Lupa password?</a>
                    </div>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-200 shadow-lg shadow-gray-200">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar</a>
                </p>
            </div>
        </div>
        
        <div class="bg-blue-50 p-4 text-center border-t border-blue-100">
            <p class="text-[10px] text-blue-400 uppercase font-bold tracking-widest">Portal Login Altik-Mart</p>
        </div>
    </div>

</body>
</html>