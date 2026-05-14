<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AksiAlam - Solusi Ekologi Masyarakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="text-2xl font-black text-green-600 tracking-tighter">AksiAlam.</h1>
            <nav class="flex items-center space-x-6">
                <a href="/" class="text-sm font-semibold text-slate-600 hover:text-green-600">Beranda</a>
                @auth
                    <a href="{{ route('reports.create') }}" class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-full hover:bg-green-700 transition">Lapor Kerusakan</a>
                    <span class="text-sm font-bold text-slate-800">Halo, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-green-600">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white text-sm font-bold px-5 py-2 rounded-full hover:bg-green-700 transition">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <section class="relative bg-slate-900 pt-32 pb-56 overflow-hidden">
        <div class="absolute inset-0 opacity-50">
            <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=2000" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-transparent to-slate-900/80"></div>

        <div class="relative max-w-7xl mx-auto px-4 text-center z-10">
            <h2 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tight">
                Pulihkan Alam <br>
                <span class="text-green-400">Dimulai Dari Laporanmu.</span>
            </h2>
            
            <p class="text-lg md:text-xl text-slate-200 mb-12 max-w-4xl mx-auto font-medium leading-relaxed">
                AksiAlam hadir sebagai wadah gotong royong digital pertama untuk restorasi ekologi di Indonesia. Kami menjembatani kepedulian warga sipil dengan aksi nyata—mulai dari penanganan krisis tumpukan sampah, perbaikan fasilitas ruang terbuka hijau, hingga perlindungan habitat flora dan fauna dari tangan tidak bertanggung jawab. <br><br>
                Jangan biarkan kerusakan alam hanya menjadi tontonan. Jadilah bagian dari solusi, laporkan masalah di sekitarmu, dan bergabunglah bersama ratusan relawan untuk memulihkan bumi pertiwi.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('reports.create') }}" class="bg-green-500 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-green-400 hover:scale-105 hover:shadow-lg hover:shadow-green-500/30 transition-all duration-300">
                    Buat Laporan Sekarang
                </a>
                <a href="#eksplorasi" class="bg-white/10 backdrop-blur-md text-white border border-white/30 px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-slate-900 transition-all duration-300">
                    Cari Aksi Relawan
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 -mt-12 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex items-center space-x-4">
                <div class="bg-orange-100 p-3 rounded-xl text-orange-600 font-bold text-xl">📢</div>
                <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Laporan Tervalidasi</p><h4 class="text-2xl font-black">{{ $stats['reports'] ?? 0 }}</h4></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-xl text-green-600 font-bold text-xl">🤝</div>
                <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Relawan Aktif</p><h4 class="text-2xl font-black">{{ $stats['volunteers'] ?? 0 }}</h4></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600 font-bold text-xl">📈</div>
                <div><p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Kg Sampah Terangkat</p><h4 class="text-2xl font-black">{{ $stats['impact'] ?? 0 }}</h4></div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 py-20">
        
        <section id="eksplorasi" class="mb-12">
            <form action="/" method="GET" class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <input type="text" name="search" placeholder="Cari lokasi atau masalah..." value="{{ request('search') }}" class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-green-500 font-medium">
                <select name="category" class="px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-green-500 font-medium cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="sampah" {{ request('category') == 'sampah' ? 'selected' : '' }}>Masalah Sampah</option>
                    <option value="fasilitas" {{ request('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas Alam</option>
                    <option value="flora_fauna" {{ request('category') == 'flora_fauna' ? 'selected' : '' }}>Flora & Fauna</option>
                </select>
                <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-slate-800 transition">Cari Aksi</button>
            </form>
        </section>

        <div class="w-full mb-24">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse ($reports as $report)
                    <x-report-card :report="$report" />
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-slate-100 border-dashed">
                        <p class="text-slate-400 font-bold text-lg">Hasil tidak ditemukan. Coba kata kunci lain atau jadilah yang pertama melapor!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-900 rounded-3xl p-8 lg:p-12 text-white shadow-2xl">
            
            <div>
                <h5 class="font-black text-2xl mb-6 flex items-center text-white"><span class="mr-3 text-3xl">🏆</span> Top Relawan Aktif</h5>
                <div class="space-y-4">
                    @if(isset($leaderboard) && count($leaderboard) > 0)
                        @foreach ($leaderboard as $index => $topUser)
                        <div class="flex items-center justify-between bg-white/10 p-3 rounded-xl border border-white/5 hover:bg-white/20 transition duration-300">
                            <div class="flex items-center space-x-4">
                                <span class="w-6 text-green-400 font-bold text-lg text-center">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center font-bold uppercase shadow-inner">{{ substr($topUser->name, 0, 2) }}</div>
                                <p class="font-bold text-slate-100">{{ $topUser->name }}</p>
                            </div>
                            <span class="text-sm font-black bg-green-500/20 text-green-300 px-3 py-1.5 rounded-lg">{{ $topUser->exp_points }} XP</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-slate-400 italic">Belum ada data relawan.</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col justify-center bg-green-600 rounded-2xl p-8 shadow-inner overflow-hidden relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-black/10 rounded-full blur-2xl"></div>
                
                <h5 class="font-black text-2xl mb-3 text-white relative z-10">Mendukung UN SDGs</h5>
                <p class="text-green-100 mb-8 font-medium leading-relaxed relative z-10">AksiAlam berkontribusi langsung pada tujuan pembangunan berkelanjutan global PBB melalui aksi komunitas lokal yang terukur.</p>
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-xl font-bold text-sm border border-white/20 hover:bg-white/30 hover:scale-105 transition-all duration-300 cursor-default">🌍 13. Climate Action</div>
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-xl font-bold text-sm border border-white/20 hover:bg-white/30 hover:scale-105 transition-all duration-300 cursor-default">🐟 14. Life Below Water</div>
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-xl font-bold text-sm border border-white/20 hover:bg-white/30 hover:scale-105 transition-all duration-300 cursor-default">🌲 15. Life On Land</div>
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-xl font-bold text-sm border border-white/20 hover:bg-white/30 hover:scale-105 transition-all duration-300 cursor-default">🤝 17. Partnerships</div>
                </div>
            </div>
        </div>

        <section class="mt-24 pt-20 border-t border-slate-200">
            <div class="text-center mb-10">
                <h3 class="text-3xl font-black mb-2 uppercase tracking-tighter text-slate-800">Bukti Dampak Terukur</h3>
                <p class="text-slate-500 font-medium">Kami menjamin setiap donasi tenaga tersalurkan secara transparan.</p>
            </div>
            
            <x-before-after-slider 
                imageBefore="{{ asset('images/before.jpeg') }}" 
                imageAfter="{{ asset('images/after.jpeg') }}" 
            />
        </section>

    </main>

    <footer class="bg-white border-t border-slate-200 py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-xl font-black text-green-600 tracking-tighter mb-2">AksiAlam.</h2>
            <p class="text-slate-400 text-sm font-bold">© 2026 Dikembangkan untuk Keperluan Akademis di Surabaya.</p>
        </div>
    </footer>

</body>
</html>