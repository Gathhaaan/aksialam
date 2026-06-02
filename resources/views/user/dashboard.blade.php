<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Relawan - AksiAlam</title>
    @include('components.head')
    <style>
        .xp-bar-bg { background: #e2e8f0; border-radius: 9999px; height: 10px; }
        .xp-bar-fill { background: linear-gradient(90deg, #22c55e, #16a34a); border-radius: 9999px; height: 10px; transition: width 1s ease; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; color: #94a3b8; transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(34,197,94,0.15); color: #22c55e; }
        .card-stat { background: white; border-radius: 20px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .badge { padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-verified { background: #dcfce7; color: #166534; }
        .badge-resolved { background: #dbeafe; color: #1e40af; }
        .badge-open     { background: #dcfce7; color: #166534; }
        .badge-finished { background: #e0e7ff; color: #3730a3; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased" style="display:flex; min-height:100vh;">

    {{-- ===== SIDEBAR ===== --}}
    <aside style="width:260px; min-height:100vh; background:#0f172a; display:flex; flex-direction:column; position:fixed; top:0; left:0; z-index:40; padding:32px 16px;">
        <a href="{{ route('home') }}" class="text-2xl font-black text-green-500 tracking-tighter mb-2 px-4">AksiAlam.</a>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest px-4 mb-8">Portal Relawan</p>

        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link active">
                <span class="text-xl">🏠</span> Dashboard
            </a>
            <a href="{{ route('user.reports.create') }}" class="sidebar-link">
                <span class="text-xl">📢</span> Buat Laporan
            </a>
            <a href="{{ route('home') }}" class="sidebar-link" style="text-decoration:none; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem;">
                <span class="text-xl">🌍</span> Ke Beranda Utama
            </a>
        </nav>

        {{-- User Profile Mini --}}
        <div style="border-top:1px solid rgba(255,255,255,0.08); padding-top:20px; margin-top:20px;">
            <div class="flex items-center gap-3 px-2 mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center font-black text-white uppercase text-sm">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-green-400 text-xs font-bold">Relawan Aktif</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left" style="color:#f87171;">
                    <span class="text-xl">🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <main style="margin-left:260px; flex:1; padding:32px;">

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 font-bold px-5 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Selamat Datang, {{ $user->name }}! 👋</h1>
                <p class="text-slate-500 font-medium mt-1">Ini adalah dashboard pribadimu sebagai Relawan AksiAlam.</p>
            </div>
            <a href="{{ route('user.rewards.index') }}" class="inline-flex items-center gap-2 bg-amber-500 text-white font-bold px-6 py-3 rounded-full hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">
                <span class="text-xl">🎁</span> Tukar Reward
            </a>
        </div>

        {{-- XP Card --}}
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 rounded-3xl p-8 text-white mb-8 relative overflow-hidden shadow-xl shadow-green-500/20">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-black/10 rounded-full blur-xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-green-100 text-sm font-bold uppercase tracking-widest mb-1">Level Kamu Saat Ini</p>
                    <h2 class="text-5xl font-black">{{ $user->exp_points }} <span class="text-2xl font-bold">XP</span></h2>
                    <p class="text-green-100 mt-2 font-medium">Peringkat #{{ $userRank }} dari semua Relawan Aktif</p>
                </div>
                <div class="text-6xl">🏆</div>
            </div>
            <div class="mt-6 relative z-10">
                <p class="text-green-100 text-xs font-bold mb-2">Progress ke level berikutnya</p>
                <div class="xp-bar-bg bg-white/30">
                    <div class="xp-bar-fill bg-white" style="width: {{ min(($user->exp_points % 100), 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="card-stat text-center">
                <p class="text-3xl font-black text-slate-900">{{ $myReports->count() }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Laporan Saya</p>
            </div>
            <div class="card-stat text-center">
                <p class="text-3xl font-black text-green-600">{{ $myCampaigns->count() }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Aksi Diikuti</p>
            </div>
            <div class="card-stat text-center">
                <p class="text-3xl font-black text-blue-600">{{ $userRank }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Peringkat</p>
            </div>
            <div class="card-stat text-center">
                <p class="text-3xl font-black text-amber-500">{{ $user->exp_points }}</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total XP</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Laporan Saya --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-black text-slate-800">📋 Laporan Saya</h2>
                    <a href="{{ route('user.reports.create') }}" class="bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-full hover:bg-green-700 transition">+ Buat Laporan</a>
                </div>
                <div class="space-y-3">
                    @forelse($myReports as $report)
                        <div class="bg-white rounded-2xl p-4 border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                            @if($report->image_url)
                                <img src="{{ $report->image_url }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🌿</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 truncate">{{ $report->title }}</p>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $report->location_name }}</p>
                            </div>
                            <span class="badge badge-{{ $report->status }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl p-10 border border-dashed border-slate-200 text-center">
                            <p class="text-4xl mb-3">📭</p>
                            <p class="text-slate-400 font-bold">Kamu belum membuat laporan.</p>
                            <a href="{{ route('user.reports.create') }}" class="inline-block mt-4 bg-green-600 text-white px-5 py-2 rounded-full font-bold text-sm hover:bg-green-700 transition">Buat Laporan Pertama</a>
                        </div>
                    @endforelse
                </div>

                {{-- Kampanye yang Diikuti --}}
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-black text-slate-800">✅ Kampanye yang Diikuti</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($myCampaigns as $campaign)
                            <div class="bg-white rounded-2xl p-5 border border-slate-100 hover:shadow-md transition group">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-bold text-slate-800 leading-tight group-hover:text-green-600 transition">{{ $campaign->title }}</h3>
                                    <span class="badge badge-{{ $campaign->status }} flex-shrink-0">{{ ucfirst($campaign->status) }}</span>
                                </div>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $campaign->description }}</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-slate-400 font-bold">Oleh: {{ $campaign->organizer->name ?? 'Tim AksiAlam' }}</p>
                                    <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-green-600 text-xs font-bold hover:underline">Lihat Detail &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 bg-white rounded-2xl p-8 border border-dashed border-slate-200 text-center">
                                <p class="text-3xl mb-2">🌱</p>
                                <p class="text-slate-400 font-bold text-sm">Kamu belum mengikuti kampanye aksi apapun.</p>
                                <a href="{{ route('home') }}" class="inline-block mt-3 bg-green-50 text-green-700 px-4 py-2 rounded-full font-bold text-xs hover:bg-green-100 transition">Jelajahi Feed Laporan</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Leaderboard --}}
            <div>
                <h2 class="text-xl font-black text-slate-800 mb-4">🏅 Top Relawan</h2>
                <div class="bg-slate-900 rounded-3xl p-6 space-y-3">
                    @foreach($leaderboard as $index => $topUser)
                        <div class="flex items-center gap-3 p-3 rounded-xl {{ $topUser->id === $user->id ? 'bg-green-500/20 border border-green-500/30' : 'bg-white/5 hover:bg-white/10' }} transition">
                            <span class="text-lg font-black {{ $index === 0 ? 'text-yellow-400' : ($index === 1 ? 'text-slate-300' : ($index === 2 ? 'text-amber-600' : 'text-slate-500')) }} w-6 text-center">
                                {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : $index+1)) }}
                            </span>
                            <div class="w-9 h-9 bg-green-600 rounded-full flex items-center justify-center text-white font-black text-xs uppercase">
                                {{ substr($topUser->name, 0, 2) }}
                            </div>
                            <p class="text-white font-bold text-sm flex-1 truncate">{{ $topUser->name }}</p>
                            <span class="text-green-400 font-black text-sm">{{ $topUser->exp_points }}<span class="text-xs text-slate-400"> XP</span></span>
                        </div>
                    @endforeach
                </div>


            </div>
            
            {{-- Riwayat Reward --}}
            <div class="mt-8">
                <h2 class="text-xl font-black text-slate-800 mb-4">🎁 Riwayat Reward</h2>
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm space-y-3">
                    @forelse($myRewards as $myReward)
                        <div class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:shadow-sm transition">
                            @if($myReward->image_url)
                                <img src="{{ $myReward->image_url }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">🎁</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 truncate leading-tight">{{ $myReward->name }}</p>
                                <p class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($myReward->pivot->created_at)->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="badge {{ $myReward->pivot->status === 'claimed' ? 'badge-verified' : 'badge-pending' }}">
                                {{ $myReward->pivot->status === 'claimed' ? 'Terkirim' : 'Diproses' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400">
                            <p class="text-3xl mb-2">🎁</p>
                            <p class="font-bold text-sm">Belum ada reward yang ditukar.</p>
                            <a href="{{ route('user.rewards.index') }}" class="text-green-600 text-xs font-bold hover:underline mt-1 inline-block">Tukar sekarang!</a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

</body>
</html>
