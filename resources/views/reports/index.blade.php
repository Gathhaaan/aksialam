<!DOCTYPE html>
<html lang="id">
<head>
    <title>Beranda - AksiAlam</title>
    
    @include('components.head')
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="text-2xl font-black text-green-600 tracking-tighter">AksiAlam.</h1>
            <nav class="flex items-center space-x-6">
                @auth
                    @php
                        $role = auth()->user()->role;
                        $dashboardRoute = $role === 'admin' ? 'admin.dashboard' : ($role === 'organizer' ? 'organizer.dashboard' : 'user.dashboard');
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="text-sm font-semibold text-slate-600 hover:text-green-600">Dashboard</a>
                    <a href="{{ route('reports.create') }}" class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-full hover:bg-green-700 transition">Lapor Kerusakan</a>
                    <span class="text-sm font-bold text-slate-800">Halo, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700">Keluar</button>
                    </form>
                @else
                    <a href="/" class="text-sm font-semibold text-slate-600 hover:text-green-600">Beranda</a>
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
                <a href="#kampanye-aktif" class="bg-white/10 backdrop-blur-md text-white border border-white/30 px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-slate-900 transition-all duration-300">
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
        
        <section id="peta-laporan" class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-black text-slate-800">Peta Sebaran Kerusakan (Real-Time)</h3>
            </div>
            <div id="main-map" class="w-full h-[500px] rounded-3xl border-4 border-white shadow-xl z-10"></div>
            
            {{-- Keterangan Warna Pin (Legend) --}}
            <div class="mt-4 flex flex-wrap gap-6 items-center justify-center text-sm font-bold text-slate-600">
                <div class="flex items-center gap-2">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" class="h-6 object-contain" alt="Merah">
                    <span>Laporan Masuk (Pending)</span>
                </div>
                <div class="flex items-center gap-2">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png" class="h-6 object-contain" alt="Biru">
                    <span>Kampanye / Aksi Relawan</span>
                </div>
                <div class="flex items-center gap-2">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" class="h-6 object-contain" alt="Hijau">
                    <span>Selesai Teratasi (Tuntas)</span>
                </div>
            </div>
        </section>

        {{-- ==========================================
             SECTION 1: KAMPANYE AKTIF 🟢
        ========================================== --}}
        <section id="kampanye-aktif" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">🟢 Kampanye Aksi Berlangsung</h3>
                    <p class="text-slate-500 text-sm mt-1">Bergabunglah dengan relawan yang sedang berjuang di lapangan sekarang</p>
                </div>
                <span class="bg-green-100 text-green-700 font-bold text-sm px-4 py-2 rounded-full">{{ $activeCampaigns->count() }} Aktif</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activeCampaigns as $campaign)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">
                    {{-- Image --}}
                    <div class="h-40 bg-gradient-to-br from-green-400 to-emerald-600 relative overflow-hidden flex-shrink-0">
                        <div class="absolute inset-0 flex items-center justify-center text-5xl opacity-30">🌿</div>
                        <div class="absolute top-3 left-3">
                            <span class="bg-green-500 text-white text-[10px] font-black px-2 py-1 rounded-md shadow-sm">🟢 BUKA</span>
                        </div>
                    </div>
                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        <h4 class="text-lg font-black text-slate-900 mb-2 leading-snug">{{ $campaign->title }}</h4>
                        <p class="text-slate-500 text-xs mb-4 flex-1">{{ Str::limit($campaign->description, 80) }}</p>
                        
                        <div class="flex flex-col gap-1 text-xs text-slate-500 mb-4">
                            @if($campaign->location_name)
                                <span class="truncate">📍 {{ $campaign->location_name }}</span>
                            @endif
                            @if($campaign->event_date)
                                <span>📅 {{ \Carbon\Carbon::parse($campaign->event_date)->translatedFormat('d F Y') }}</span>
                            @endif
                        </div>
                        
                        {{-- Progress Bar --}}
                        @php
                            $volunteersCount = $campaign->volunteers->count();
                            $maxVolunteers = $campaign->max_volunteers ?? 100;
                            $progress = $maxVolunteers > 0 ? min(100, round($volunteersCount / $maxVolunteers * 100)) : 0;
                        @endphp
                        <div class="mb-1 flex justify-between text-[10px] text-slate-500 font-bold">
                            <span>Relawan: {{ $volunteersCount }}/{{ $maxVolunteers }}</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-4">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        
                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="block text-center bg-green-50 text-green-700 font-bold py-2 rounded-xl hover:bg-green-600 hover:text-white transition-colors text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-4xl mb-3">🌱</p>
                    <p class="text-slate-400 font-bold">Belum ada kampanye yang sedang berlangsung.</p>
                </div>
            @endforelse
            </div>
        </section>

        {{-- ==========================================
             SECTION 2: KAMPANYE LAINNYA 📁
        ========================================== --}}
        @if($otherCampaigns->count() > 0)
        <section id="kampanye-lainnya" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">📁 Kampanye Selesai & Ditutup</h3>
                    <p class="text-slate-500 text-sm mt-1">Rekam jejak aksi nyata komunitas AksiAlam</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($otherCampaigns as $campaign)
                    <a href="{{ route('campaigns.show', $campaign->id) }}" class="block bg-white rounded-2xl border border-slate-100 p-5 hover:shadow-md transition-all duration-300 opacity-80 hover:opacity-100">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="font-bold text-slate-700 text-sm leading-snug flex-1 mr-2">{{ $campaign->title }}</h4>
                            @if($campaign->status === 'finished')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-lg whitespace-nowrap flex-shrink-0">✅ Selesai</span>
                            @else
                                <span class="bg-slate-100 text-slate-500 text-xs font-bold px-2 py-1 rounded-lg whitespace-nowrap flex-shrink-0">🔒 Ditutup</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mb-2">{{ Str::limit($campaign->description, 70) }}</p>
                        @if($campaign->location_name)
                            <p class="text-xs text-slate-400">📍 {{ $campaign->location_name }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">🤝 {{ $campaign->volunteers->count() }} relawan berpartisipasi</p>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ==========================================
             SECTION 3: LAPORAN WARGA 📢 (Dipertahankan)
        ========================================== --}}
        <section id="laporan-warga" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">📢 Laporan dari Warga</h3>
                    <p class="text-slate-500 text-sm mt-1">Pantau kondisi lingkungan yang dilaporkan komunitas di seluruh Indonesia</p>
                </div>
            </div>

            {{-- Search & Filter --}}
            <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-8">
                <input type="text" name="search" placeholder="Cari lokasi atau masalah..." value="{{ request('search') }}" class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-green-500 font-medium">
                <select name="category" class="px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-green-500 font-medium cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="sampah" {{ request('category') == 'sampah' ? 'selected' : '' }}>Masalah Sampah</option>
                    <option value="fasilitas" {{ request('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas Alam</option>
                    <option value="flora_fauna" {{ request('category') == 'flora_fauna' ? 'selected' : '' }}>Flora & Fauna</option>
                </select>
                <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-slate-800 transition">Cari</button>
            </form>

            <div class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    .flex.overflow-x-auto::-webkit-scrollbar { display: none; }
                </style>
                @forelse ($reports as $report)
                    <div class="snap-start shrink-0 w-[300px] sm:w-[350px]">
                        <x-report-card :report="$report" />
                    </div>
                @empty
                    <div class="w-full py-20 text-center bg-white rounded-2xl border border-slate-100 border-dashed">
                        <p class="text-slate-400 font-bold text-lg">Hasil tidak ditemukan. Coba kata kunci lain atau jadilah yang pertama melapor!</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ==========================================
             SECTION 4: BERITA EKOLOGI INDONESIA 📰
        ========================================== --}}
        <section id="berita-ekologi" class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">📰 Berita Ekologi Indonesia Terkini</h3>
                    <p class="text-slate-500 text-sm mt-1">Tetap update dengan kondisi lingkungan hidup di tanah air</p>
                </div>
            </div>

            @if(count($ecoNews) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ecoNews as $news)
                        @if(!empty($news['title']) && $news['title'] !== '[Removed]')
                        <a href="{{ $news['url'] }}" target="_blank" rel="noopener noreferrer" 
                           class="block bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                            {{-- News Image --}}
                            <div class="h-44 bg-slate-100 overflow-hidden relative">
                                @if(!empty($news['urlToImage']))
                                    <img src="{{ $news['urlToImage'] }}" alt="{{ $news['title'] }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-4xl\'>🌿</div>'">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center text-4xl">🌿</div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                            {{-- News Content --}}
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $news['source']['name'] ?? 'Berita' }}</span>
                                    @if(!empty($news['publishedAt']))
                                        <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($news['publishedAt'])->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-3 group-hover:text-green-700 transition-colors">
                                    {{ $news['title'] }}
                                </h4>
                                @if(!empty($news['description']))
                                    <p class="text-xs text-slate-400 mt-2 line-clamp-2">{{ $news['description'] }}</p>
                                @endif
                                <div class="mt-3 flex items-center text-green-600 text-xs font-bold">
                                    Baca Selengkapnya <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                                </div>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-5xl mb-4">📡</p>
                    <p class="text-slate-400 font-bold">Berita sedang dimuat...</p>
                    <p class="text-slate-400 text-sm mt-1">Silakan refresh halaman dalam beberapa saat.</p>
                </div>
            @endif
        </section>

        {{-- Leaderboard & SDGs --}}
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
                before="{{ asset('images/before.jpeg') }}" 
                after="{{ asset('images/after.jpeg') }}" 
            />
        </section>

    </main>

    <footer class="bg-white border-t border-slate-200 py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-xl font-black text-green-600 tracking-tighter mb-2">AksiAlam.</h2>
            <p class="text-slate-400 text-sm font-bold">© 2026 Dikembangkan untuk Keperluan Akademis di Surabaya.</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Peta (Center Indonesia) dengan mematikan scroll zoom agar tidak mengganggu scroll halaman
            var map = L.map('main-map', {
                scrollWheelZoom: false
            }).setView([-2.5489, 118.0149], 5);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Data Reports dari backend (PHP ke JS)
            var reportsData = @json($reports);
            
            var greenIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            reportsData.forEach(function(report) {
                if(report.latitude && report.longitude) {
                    var iconToUse = report.status === 'resolved' ? greenIcon : redIcon;
                    var marker = L.marker([report.latitude, report.longitude], {icon: iconToUse}).addTo(map);
                    
                    var popupContent = `
                        <div class="font-sans">
                            <strong class="text-green-600">${report.title}</strong><br>
                            <span class="text-xs text-slate-500">${report.location_name}</span><br>
                            <a href="/reports/${report.id}" class="text-xs font-bold text-blue-500 hover:underline mt-2 inline-block">Lihat Detail &rarr;</a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
            });

            // ---- PIN KAMPANYE AKSI (Ikon Biru) ----
            var blueIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var campaignsData = @json($campaignsForMap ?? []);
            campaignsData.forEach(function(campaign) {
                if(campaign.latitude && campaign.longitude) {
                    var statusLabel = campaign.status === 'open' ? '🟢 Buka Pendaftaran' : (campaign.status === 'finished' ? '✅ Selesai' : '🔒 Ditutup');
                    var popupContent = `
                        <div style="font-family:inherit; min-width:160px;">
                            <p style="font-weight:bold; color:#1e3a8a; margin:0 0 4px 0;">📣 ${campaign.title}</p>
                            <p style="font-size:11px; margin:0 0 4px 0; color:#64748b;">📍 ${campaign.location_name}</p>
                            <p style="font-size:11px; margin:0 0 8px 0; color:#64748b;">📅 ${campaign.event_date ?? '-'}</p>
                            <span style="font-size:10px; font-weight:bold; background:#dbeafe; color:#1d4ed8; padding:2px 6px; border-radius:4px;">${statusLabel}</span>
                            <br>
                            <a href="/campaign/${campaign.id}" style="font-size:11px; font-weight:bold; color:#16a34a; margin-top:6px; display:inline-block;">Lihat Aksi &rarr;</a>
                        </div>
                    `;
                    L.marker([parseFloat(campaign.latitude), parseFloat(campaign.longitude)], {icon: blueIcon})
                        .addTo(map)
                        .bindPopup(popupContent);
                }
            });
            // ---- AKHIR PIN KAMPANYE ----
        });
    </script>
</body>
</html>