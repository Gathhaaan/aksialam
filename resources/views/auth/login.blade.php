<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AksiAlam</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-slate-900 mb-6">Masuk ke AksiAlam</h2>
        
        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500 pr-10" required>
                    
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-green-600 focus:outline-none transition-colors">
                        <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            @error('email')
                <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-green-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-green-700 transition">Masuk</button>
        </form>
        <p class="text-center text-sm text-slate-500 mt-4">Belum punya akun? <a href="{{ route('register') }}" class="text-green-600 font-medium">Daftar di sini</a></p>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Cek tipe input saat ini (jika password jadikan text, jika text jadikan password)
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Ubah warna ikon saat password terlihat
            if(type === 'text') {
                eyeIcon.classList.remove('text-slate-400');
                eyeIcon.classList.add('text-green-600');
            } else {
                eyeIcon.classList.remove('text-green-600');
                eyeIcon.classList.add('text-slate-400');
            }
        });
    </script>
</body>
</html>