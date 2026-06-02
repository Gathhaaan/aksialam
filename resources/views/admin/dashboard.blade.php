<!DOCTYPE html>
<html lang="id">
<head>
    <title>Super Admin Dashboard - AksiAlam</title>
    @include('components.head')
    <style>
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:12px; font-weight:600; font-size:0.9rem; color:#94a3b8; transition:all 0.2s; text-decoration:none; cursor:pointer; background:none; border:none; width:100%; text-align:left; }
        .sidebar-link:hover, .sidebar-link.active { background:rgba(168,85,247,0.15); color:#c084fc; }
        .stat-card { background:white; border-radius:20px; padding:24px; border:1px solid #f1f5f9; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .badge { padding:3px 10px; border-radius:999px; font-size:0.72rem; font-weight:700; display:inline-block; }
        .badge-pending  { background:#fef3c7; color:#92400e; }
        .badge-verified { background:#dcfce7; color:#166534; }
        .badge-resolved { background:#dbeafe; color:#1e40af; }
        .badge-rejected { background:#fee2e2; color:#991b1b; }
        .badge-open     { background:#dcfce7; color:#166534; }
        .badge-finished { background:#e0e7ff; color:#3730a3; }
        .badge-user     { background:#f1f5f9; color:#475569; }
        .badge-organizer{ background:#ede9fe; color:#6d28d9; }
        .badge-admin    { background:#fce7f3; color:#9d174d; }
        .tab-btn { padding:8px 20px; border-radius:999px; font-weight:700; font-size:0.85rem; cursor:pointer; border:none; background:#f1f5f9; color:#64748b; transition:all 0.2s; }
        .tab-btn.active { background:#7c3aed; color:white; }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased" style="display:flex; min-height:100vh;">

    {{-- ===== SIDEBAR ===== --}}
    <aside style="width:260px; min-height:100vh; background:#0f172a; display:flex; flex-direction:column; position:fixed; top:0; left:0; z-index:40; padding:32px 16px;">
        <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter mb-2 px-4" style="color:#c084fc;">AksiAlam.</a>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest px-4 mb-8">Super Admin Panel</p>

        <nav class="flex flex-col gap-1 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
                <span class="text-xl">🛡️</span> Dashboard
            </a>
            <button onclick="switchTab('tab-reports')" class="sidebar-link">
                <span class="text-xl">📋</span> Kelola Laporan
            </button>
            <button onclick="switchTab('tab-users')" class="sidebar-link">
                <span class="text-xl">👥</span> Kelola User
            </button>
            <button onclick="switchTab('tab-campaigns')" class="sidebar-link">
                <span class="text-xl">📣</span> Semua Kampanye
            </button>
            <a href="{{ route('home') }}" class="sidebar-link" style="text-decoration:none; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem;">
                <span class="text-xl">🌍</span> Ke Beranda Utama
            </a>
        </nav>

        <div style="border-top:1px solid rgba(255,255,255,0.08); padding-top:20px; margin-top:20px;">
            <div class="flex items-center gap-3 px-2 mb-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-white uppercase text-sm" style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs font-bold" style="color:#c084fc;">Super Admin</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link" style="color:#f87171;">
                    <span class="text-xl">🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <main style="margin-left:260px; flex:1; padding:32px;">

        @if(session('success'))
            <div class="bg-purple-100 border border-purple-300 text-purple-800 font-bold px-5 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 font-bold px-5 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900">Super Admin Panel 🛡️</h1>
            <p class="text-slate-500 font-medium mt-1">Kontrol penuh atas seluruh sistem AksiAlam.</p>
        </div>

        {{-- Stats Baris 1 --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Relawan</p>
                <p class="text-4xl font-black text-slate-900">{{ $stats['total_users'] }}</p>
                <div class="mt-3 h-1.5 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-purple-500" style="width:70%"></div></div>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Organizer</p>
                <p class="text-4xl font-black text-purple-600">{{ $stats['total_organizers'] }}</p>
                <div class="mt-3 h-1.5 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-purple-400" style="width:40%"></div></div>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Laporan</p>
                <p class="text-4xl font-black text-orange-500">{{ $stats['total_reports'] }}</p>
                <div class="mt-3 h-1.5 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-orange-400" style="width:60%"></div></div>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Laporan Pending</p>
                <p class="text-4xl font-black text-red-500">{{ $stats['pending_reports'] }}</p>
                <div class="mt-3 h-1.5 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-red-400" style="width:{{ $stats['total_reports'] > 0 ? ($stats['pending_reports']/$stats['total_reports']*100) : 0 }}%"></div></div>
            </div>
        </div>

        {{-- Stats Baris 2 --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Laporan Terverif.</p>
                <p class="text-4xl font-black text-green-600">{{ $stats['verified_reports'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Kampanye</p>
                <p class="text-4xl font-black text-blue-600">{{ $stats['total_campaigns'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kampanye Aktif</p>
                <p class="text-4xl font-black text-emerald-600">{{ $stats['open_campaigns'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Volunteer</p>
                <p class="text-4xl font-black text-indigo-600">{{ $stats['total_volunteers'] }}</p>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex gap-2 mb-6">
            <button class="tab-btn active" id="btn-reports" onclick="switchTab('tab-reports')">📋 Laporan</button>
            <button class="tab-btn" id="btn-users" onclick="switchTab('tab-users')">👥 Users</button>
            <button class="tab-btn" id="btn-campaigns" onclick="switchTab('tab-campaigns')">📣 Kampanye</button>
        </div>

        {{-- ===== TAB: LAPORAN ===== --}}
        <div id="tab-reports" class="tab-content active">
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-800">Semua Laporan Terbaru</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 text-xs font-black text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Laporan</th>
                                <th class="px-6 py-3 text-left">Pelapor</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentReports as $report)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($report->image_url)
                                            <img src="{{ $report->image_url }}" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">🌿</div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ Str::limit($report->title, 35) }}</p>
                                            <p class="text-xs text-slate-400">📍 {{ $report->location_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $report->user->name ?? 'Anonim' }}</td>
                                <td class="px-6 py-4"><span class="badge badge-pending">{{ $report->category }}</span></td>
                                <td class="px-6 py-4"><span class="badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.reports.status', $report->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <select name="status" class="text-xs font-bold px-2 py-1.5 rounded-lg bg-slate-100 border-none">
                                            <option value="pending"  {{ $report->status=='pending'  ? 'selected':'' }}>Pending</option>
                                            <option value="verified" {{ $report->status=='verified' ? 'selected':'' }}>Verified</option>
                                            <option value="resolved" {{ $report->status=='resolved' ? 'selected':'' }}>Resolved</option>
                                            <option value="rejected" {{ $report->status=='rejected' ? 'selected':'' }}>Rejected</option>
                                        </select>
                                        <button type="submit" class="bg-purple-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-purple-700 transition">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center text-slate-400 font-bold">Belum ada laporan masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===== TAB: USERS ===== --}}
        <div id="tab-users" class="tab-content">
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-800">Semua User Terdaftar</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 text-xs font-black text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-left">XP</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentUsers as $u)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-white text-xs uppercase {{ $u->role==='admin' ? 'bg-purple-600' : ($u->role==='organizer' ? 'bg-blue-500' : 'bg-green-500') }}">
                                            {{ substr($u->name,0,2) }}
                                        </div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $u->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $u->email }}</td>
                                <td class="px-6 py-4"><span class="badge badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                                <td class="px-6 py-4 font-black text-slate-700">{{ $u->exp_points }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        {{-- Ubah Role --}}
                                        <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            <select name="role" class="text-xs font-bold px-2 py-1.5 rounded-lg bg-slate-100 border-none">
                                                <option value="user"      {{ $u->role==='user'      ? 'selected':'' }}>User</option>
                                                <option value="organizer" {{ $u->role==='organizer' ? 'selected':'' }}>Organizer</option>
                                                <option value="admin"     {{ $u->role==='admin'     ? 'selected':'' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-2 py-1.5 rounded-lg hover:bg-blue-700 transition">OK</button>
                                        </form>
                                        {{-- Hapus User --}}
                                        @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user {{ $u->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1.5 rounded-lg hover:bg-red-200 transition">🗑</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center text-slate-400 font-bold">Belum ada user terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===== TAB: KAMPANYE ===== --}}
        <div id="tab-campaigns" class="tab-content">
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-black text-slate-800">Semua Kampanye</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 text-xs font-black text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Kampanye</th>
                                <th class="px-6 py-3 text-left">Organizer</th>
                                <th class="px-6 py-3 text-left">Relawan</th>
                                <th class="px-6 py-3 text-left">Target</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($allCampaigns as $camp)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-sm">{{ Str::limit($camp->title, 40) }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $camp->description }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $camp->organizer->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-slate-800">{{ $camp->volunteers->count() }}</span>
                                    <span class="text-xs text-slate-400"> relawan</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ number_format($camp->target_metric) }} kg</td>
                                <td class="px-6 py-4"><span class="badge badge-{{ $camp->status }}">{{ ucfirst($camp->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-16 text-center text-slate-400 font-bold">Belum ada kampanye.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script>
        function switchTab(tabId) {
            // Sembunyikan semua tab
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            // Tampilkan tab yang dipilih
            document.getElementById(tabId).classList.add('active');
            const btnId = 'btn-' + tabId.replace('tab-', '');
            if(document.getElementById(btnId)) document.getElementById(btnId).classList.add('active');
        }
    </script>

</body>
</html>
