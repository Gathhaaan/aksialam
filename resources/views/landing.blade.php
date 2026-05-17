<!DOCTYPE html>
<html lang="id">
<head>
    <title>Selamat Datang di AksiAlam</title>
    
    @include('components.head')

    <style>
        .slide-img {
            opacity: 0;
            transition: opacity 1000ms ease-in-out;
            z-index: 10;
        }
        .slide-img.active {
            opacity: 1;
            z-index: 20;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-green-500 selection:text-white">

    <nav class="bg-slate-900 w-full fixed top-0 left-0 right-0 z-50 border-b border-white/10">
        <div class="flex justify-between p-5 max-w-7xl mx-auto items-center">
            <a href="/" class="text-2xl font-black text-green-500 tracking-tighter">AksiAlam.</a>

            {{-- Nav Links (tengah) --}}
            <div class="hidden md:flex items-center space-x-6">
                <a href="#cara-kerja" class="text-slate-400 text-sm font-semibold hover:text-green-400 transition">Cara Kerja</a>
                <a href="#tentang" class="text-slate-400 text-sm font-semibold hover:text-green-400 transition">Tentang</a>
                <a href="#cta" class="text-slate-400 text-sm font-semibold hover:text-green-400 transition">Bergabung</a>
            </div>

            {{-- Auth Buttons (kanan) --}}
            <div class="flex items-center space-x-3 sm:space-x-4">
                @auth
                    {{-- Tunjukkan nama & role --}}
                    <span class="text-slate-400 text-sm hidden md:inline">
                        Halo, <strong class="text-white">{{ Auth::user()->name }}</strong>
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-black
                            {{ Auth::user()->role === 'admin' ? 'bg-purple-500/20 text-purple-300' :
                               (Auth::user()->role === 'organizer' ? 'bg-blue-500/20 text-blue-300' : 'bg-green-500/20 text-green-300') }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </span>

                    {{-- Link ke dashboard sesuai role --}}
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-purple-600 text-white px-5 py-2 rounded-full font-bold hover:bg-purple-500 transition shadow-lg shadow-purple-600/30 text-sm">
                            🛡️ Dashboard Admin
                        </a>
                    @elseif(Auth::user()->role === 'organizer')
                        <a href="{{ route('organizer.dashboard') }}" class="bg-blue-600 text-white px-5 py-2 rounded-full font-bold hover:bg-blue-500 transition shadow-lg shadow-blue-600/30 text-sm">
                            📣 Dashboard Organizer
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="bg-green-600 text-white px-5 py-2 rounded-full font-bold hover:bg-green-500 transition shadow-lg shadow-green-600/30 text-sm">
                            🏠 Ke Beranda
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-400 font-bold hover:text-red-400 transition text-sm px-2">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 font-bold hover:text-green-400 transition text-sm sm:text-base px-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 sm:px-6 rounded-full font-bold hover:bg-green-500 transition shadow-lg shadow-green-600/30 text-sm sm:text-base">
                        Gabung Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="bg-slate-900 pt-40 pb-32 px-6 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-900"></div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <div>
                <span class="text-green-400 font-black tracking-widest uppercase text-xs sm:text-sm bg-green-500/10 px-4 py-2 rounded-full border border-green-500/20">Platform Restorasi Ekologi No. 1</span>
                <h2 class="text-5xl sm:text-6xl font-black mt-6 text-white leading-tight">Ubah Kepedulian <br>Menjadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600">Aksi Nyata.</span></h2>
                <p class="mt-6 text-slate-400 text-lg sm:text-xl leading-relaxed max-w-lg">Wadah gotong royong digital bagi warga dan komunitas untuk melaporkan serta memulihkan kerusakan lingkungan di seluruh penjuru Indonesia.</p>
                
                <div class="mt-10 flex gap-4 sm:gap-6">
                    <div class="bg-white/5 p-5 rounded-2xl border border-white/10 text-center backdrop-blur-sm flex-1 max-w-[140px]">
                        <h4 class="text-3xl font-black text-white">1.2k+</h4>
                        <p class="text-[10px] sm:text-xs text-green-400 font-black uppercase mt-1">Relawan Aktif</p>
                    </div>
                    <div class="bg-white/5 p-5 rounded-2xl border border-white/10 text-center backdrop-blur-sm flex-1 max-w-[140px]">
                        <h4 class="text-3xl font-black text-white">450+</h4>
                        <p class="text-[10px] sm:text-xs text-green-400 font-black uppercase mt-1">Aksi Berhasil</p>
                    </div>
                </div>
            </div>
            
            <div class="relative hidden lg:block">
                <div class="rounded-3xl shadow-2xl rotate-2 hover:rotate-0 transition duration-700 border-8 border-white/5 overflow-hidden relative h-[500px] w-full bg-slate-800">
                    
                    <img src="{{ asset('images/relawan1.jpg') }}" class="slide-img active absolute inset-0 w-full h-full object-cover" alt="Alam 1">
                    <img src="{{ asset('images/relawan2.jpg') }}" class="slide-img absolute inset-0 w-full h-full object-cover" alt="Alam 2">
                    <img src="{{ asset('images/relawan3.jpg') }}" class="slide-img absolute inset-0 w-full h-full object-cover" alt="Alam 3">
                    
                    <div class="absolute inset-0 bg-black/10 z-30"></div>
                </div>
                
                <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 animate-bounce z-40">
                    <div class="bg-emerald-100 p-3 rounded-full text-emerald-600 text-xl">🌱</div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Status Terbaru</p>
                        <p class="font-black text-slate-800 text-sm">Hutan Lindung Pulih</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="tentang" class="py-24 px-6 bg-white relative">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mb-6">Bukan Sekadar Portal Pengaduan Biasa.</h3>
            <p class="text-lg text-slate-500 leading-relaxed">
                <strong class="text-green-600">AksiAlam</strong> lahir dari kegelisahan melihat tumpukan sampah, fasilitas alam yang dirusak, dan habitat satwa yang terancam. Kami percaya bahwa pemerintah tidak bisa bekerja sendirian. Melalui platform ini, kami menghubungkan <span class="font-bold text-slate-800">pelapor kerusakan</span>, <span class="font-bold text-slate-800">komunitas penyelenggara</span>, dan <span class="font-bold text-slate-800">relawan lapangan</span> ke dalam satu ekosistem digital yang terstruktur dan transparan.
            </p>
        </div>
    </section>

    <section id="cara-kerja" class="py-24 px-6 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-green-600 font-bold uppercase tracking-widest text-sm">Alur Platform</span>
                <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2">Bagaimana AksiAlam Bekerja?</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow relative">
                    <div class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-2xl font-black mb-6">1</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Warga Melapor</h4>
                    <p class="text-slate-500 leading-relaxed">Menemukan kerusakan alam? Unggah foto dan deskripsikan lokasinya. Laporan Anda menjadi titik awal perubahan.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow relative">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-black mb-6">2</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Komunitas Memvalidasi</h4>
                    <p class="text-slate-500 leading-relaxed">Admin dan komunitas mitra akan memverifikasi laporan tersebut, lalu menyusun kampanye aksi swadaya yang terukur.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow relative">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl font-black mb-6">3</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Relawan Bergerak</h4>
                    <p class="text-slate-500 leading-relaxed">Ratusan relawan mendaftar melalui platform kami, turun ke lokasi, dan menyelesaikan misi pemulihan lingkungan bersama.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="py-24 px-6 bg-slate-900 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-3xl mx-auto relative z-10">
            <h3 class="text-4xl font-black text-white mb-6">Penasaran dengan Aksi yang Sedang Berjalan?</h3>
            <p class="text-xl text-slate-400 mb-10">Puluhan komunitas dan ratusan relawan sedang berdiskusi dan menyusun kekuatan di dalam platform kami. Anda diundang untuk bergabung, mengeksplorasi peta kerusakan, dan mendaftar di kampanye pertama Anda.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-green-500 text-white px-8 py-4 rounded-xl font-black text-lg hover:bg-green-400 hover:scale-105 transition-all duration-300 shadow-lg shadow-green-500/25">
                    Buat Akun Gratis Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-white/10 text-white border border-white/20 px-8 py-4 rounded-xl font-black text-lg hover:bg-white hover:text-slate-900 transition-all duration-300">
                    Sudah Punya Akun? Masuk
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 py-8 text-center">
        <p class="text-slate-500 font-bold text-sm">© 2026 AksiAlam. Solusi Ekologi Masyarakat.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const images = document.querySelectorAll('.slide-img');
            let currentIndex = 0;
            const intervalTime = 2000;

            if(images.length > 0) {
                function changeImage() {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }
                setInterval(changeImage, intervalTime);
            }
        });
    </script>
</body>
</html>