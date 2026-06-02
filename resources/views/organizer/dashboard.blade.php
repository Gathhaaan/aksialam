<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Organizer - AksiAlam</title>
    @include('components.head')
    <style>
        body { display:flex; min-height:100vh; background:#f1f5f9; font-family:sans-serif; }
        .sidebar { width:260px; min-height:100vh; background:#0f172a; display:flex; flex-direction:column; position:fixed; top:0; left:0; z-index:40; padding:32px 16px; }
        .main { margin-left:260px; flex:1; padding:32px; }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:12px; font-weight:600; font-size:0.9rem; color:#94a3b8; transition:all 0.2s; text-decoration:none; cursor:pointer; background:none; border:none; width:100%; text-align:left; }
        .sidebar-link:hover, .sidebar-link.active { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .card-stat { background:white; border-radius:20px; padding:24px; border:1px solid #f1f5f9; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .badge { padding:3px 10px; border-radius:999px; font-size:0.72rem; font-weight:700; display:inline-block; }
        .badge-pending  { background:#fef3c7; color:#92400e; }
        .badge-verified { background:#dcfce7; color:#166534; }
        .badge-open     { background:#dcfce7; color:#166534; }
        .badge-finished { background:#e0e7ff; color:#3730a3; }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
        .tab-btn { padding:8px 20px; border-radius:999px; font-weight:700; font-size:0.85rem; cursor:pointer; border:none; background:#e2e8f0; color:#64748b; transition:all 0.2s; }
        .tab-btn.active { background:#2563eb; color:white; }
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
    </style>
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="text-2xl font-black text-blue-400 tracking-tighter mb-2 px-4" style="text-decoration:none;">AksiAlam.</a>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest px-4 mb-8">Portal Organizer</p>

        <nav style="display:flex; flex-direction:column; gap:4px; flex:1;">
            <button onclick="switchTab('overview')" id="btn-overview" class="sidebar-link active">
                <span>🏠</span> Dashboard
            </button>
            <button onclick="switchTab('campaigns')" id="btn-campaigns" class="sidebar-link">
                <span>📣</span> Kampanye Saya
            </button>
            <button onclick="switchTab('reports')" id="btn-reports" class="sidebar-link">
                <span>📋</span> Validasi Laporan
            </button>
            <button onclick="openModal()" id="btn-create" class="sidebar-link">
                <span>➕</span> Buat Kampanye
            </button>
            <a href="{{ route('home') }}" class="sidebar-link" style="text-decoration:none; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem;">
                <span>🌍</span> Ke Beranda Utama
            </a>
        </nav>

        <div style="border-top:1px solid rgba(255,255,255,0.08); padding-top:20px; margin-top:20px;">
            <div style="display:flex; align-items:center; gap:12px; padding:0 8px; margin-bottom:16px;">
                <div style="width:40px; height:40px; background:#3b82f6; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:900; color:white; font-size:0.8rem; text-transform:uppercase;">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div>
                    <p style="color:white; font-weight:700; font-size:0.85rem; line-height:1.2;">{{ auth()->user()->name }}</p>
                    <p style="color:#60a5fa; font-size:0.75rem; font-weight:700;">Komunitas Penggerak</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link" style="color:#f87171;">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="main">

        @if(session('success'))
            <div style="background:#dbeafe; border:1px solid #93c5fd; color:#1e40af; font-weight:700; padding:12px 20px; border-radius:12px; margin-bottom:24px; display:flex; align-items:center; gap:8px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Judul --}}
        <div style="margin-bottom:32px;">
            <h1 style="font-size:1.875rem; font-weight:900; color:#0f172a;">Dashboard Organizer 📣</h1>
            <p style="color:#64748b; font-weight:500; margin-top:4px;">Kelola kampanye dan validasi laporan dari warga.</p>
        </div>

        {{-- Stats --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:32px;">
            <div class="card-stat" style="text-align:center;">
                <p style="font-size:2rem; font-weight:900; color:#0f172a;">{{ $stats['total_campaigns'] }}</p>
                <p style="font-size:0.7rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-top:4px;">Total Kampanye</p>
            </div>
            <div class="card-stat" style="text-align:center;">
                <p style="font-size:2rem; font-weight:900; color:#16a34a;">{{ $stats['open_campaigns'] }}</p>
                <p style="font-size:0.7rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-top:4px;">Sedang Berjalan</p>
            </div>
            <div class="card-stat" style="text-align:center;">
                <p style="font-size:2rem; font-weight:900; color:#2563eb;">{{ $stats['total_volunteers'] }}</p>
                <p style="font-size:0.7rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-top:4px;">Total Relawan</p>
            </div>
            <div class="card-stat" style="text-align:center;">
                <p style="font-size:2rem; font-weight:900; color:#7c3aed;">{{ $stats['done_campaigns'] }}</p>
                <p style="font-size:0.7rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-top:4px;">Selesai</p>
            </div>
        </div>

        {{-- ===== TAB: OVERVIEW ===== --}}
        <div id="tab-overview" class="tab-content active">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
                {{-- Ringkasan Kampanye --}}
                <div class="card-stat">
                    <h2 style="font-size:1.1rem; font-weight:900; color:#0f172a; margin-bottom:16px;">📣 Kampanye Terbaru</h2>
                    @forelse($myCampaigns->take(4) as $campaign)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:12px; margin-bottom:8px;">
                            <div style="flex:1; min-width:0;">
                                <a href="{{ route('campaigns.show', $campaign->id) }}" style="text-decoration:none;">
                                    <p style="font-weight:700; color:#0f172a; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; cursor:pointer;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#0f172a'">{{ $campaign->title }}</p>
                                </a>
                                <p style="font-size:0.72rem; color:#94a3b8; margin-top:2px;">🤝 {{ $campaign->volunteers->count() }} relawan</p>
                            </div>
                            <span class="badge badge-{{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span>
                        </div>
                    @empty
                        <p style="color:#94a3b8; font-style:italic; text-align:center; padding:20px 0;">Belum ada kampanye. <br><button onclick="openModal()" style="color:#2563eb; font-weight:700; background:none; border:none; cursor:pointer;">Buat sekarang →</button></p>
                    @endforelse
                </div>

                {{-- Ringkasan Laporan Pending --}}
                <div class="card-stat">
                    <h2 style="font-size:1.1rem; font-weight:900; color:#0f172a; margin-bottom:16px;">📋 Laporan Perlu Divalidasi</h2>
                    @forelse($pendingReports->take(4) as $report)
                        <div style="display:flex; align-items:center; gap:10px; padding:10px; background:#fefce8; border-radius:12px; margin-bottom:8px;">
                            <div style="flex:1; min-width:0;">
                                <p style="font-weight:700; color:#0f172a; font-size:0.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $report->title }}</p>
                                <p style="font-size:0.7rem; color:#92400e; margin-top:2px;">📍 {{ $report->location_name }}</p>
                            </div>
                            <form action="{{ route('organizer.reports.verify', $report->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background:#16a34a; color:white; font-size:0.72rem; font-weight:700; padding:4px 10px; border-radius:8px; border:none; cursor:pointer; white-space:nowrap;">✓ Verifikasi</button>
                            </form>
                        </div>
                    @empty
                        <p style="color:#94a3b8; font-style:italic; text-align:center; padding:20px 0;">🎉 Semua laporan sudah divalidasi!</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ===== TAB: KAMPANYE ===== --}}
        <div id="tab-campaigns" class="tab-content">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <h2 style="font-size:1.25rem; font-weight:900; color:#0f172a;">📣 Semua Kampanye Saya</h2>
                <button onclick="openModal()" style="background:#2563eb; color:white; font-weight:700; font-size:0.85rem; padding:8px 20px; border-radius:999px; border:none; cursor:pointer;">+ Buat Kampanye</button>
            </div>
            <div style="background:white; border-radius:20px; border:1px solid #f1f5f9; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                @forelse($myCampaigns as $campaign)
                    <div style="padding:20px 24px; border-bottom:1px solid #f8fafc; display:flex; align-items:center; gap:16px;">
                        <div style="flex:1; min-width:0;">
                            <a href="{{ route('campaigns.show', $campaign->id) }}" style="text-decoration:none;">
                                <p style="font-weight:900; color:#0f172a; font-size:1.1rem; cursor:pointer;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#0f172a'">{{ $campaign->title }}</p>
                            </a>
                            <p style="font-size:0.8rem; color:#64748b; margin-top:2px;">{{ Str::limit($campaign->description, 80) }}</p>
                            <p style="font-size:0.75rem; color:#94a3b8; margin-top:4px;">🤝 {{ $campaign->volunteers->count() }} Relawan · Target: {{ number_format($campaign->target_metric) }} kg</p>
                        </div>
                        <span class="badge badge-{{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span>
                    </div>
                @empty
                    <div style="padding:60px; text-align:center; color:#94a3b8; font-weight:700;">
                        📭 Belum ada kampanye. <button onclick="openModal()" style="color:#2563eb; background:none; border:none; cursor:pointer; font-weight:700;">Buat sekarang →</button>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== TAB: VALIDASI LAPORAN ===== --}}
        <div id="tab-reports" class="tab-content">
            <h2 style="font-size:1.25rem; font-weight:900; color:#0f172a; margin-bottom:20px;">📋 Laporan Menunggu Validasi</h2>
            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($pendingReports as $report)
                    <div style="background:white; border-radius:16px; padding:16px 20px; border:1px solid #f1f5f9; display:flex; align-items:center; gap:16px; box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                        @if($report->image_url)
                            <img src="{{ $report->image_url }}" style="width:56px; height:56px; border-radius:12px; object-fit:cover; flex-shrink:0;">
                        @else
                            <div style="width:56px; height:56px; background:#f1f5f9; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0;">🌿</div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <p style="font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $report->title }}</p>
                            <p style="font-size:0.78rem; color:#64748b; margin-top:2px;">📍 {{ $report->location_name }}</p>
                            <p style="font-size:0.75rem; color:#94a3b8; margin-top:1px;">👤 {{ $report->user->name ?? 'Anonim' }}</p>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:8px; flex-shrink:0;">
                            <span class="badge badge-pending">PENDING</span>
                            <form action="{{ route('organizer.reports.verify', $report->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background:#16a34a; color:white; font-size:0.78rem; font-weight:700; padding:6px 14px; border-radius:8px; border:none; cursor:pointer;">✓ Verifikasi</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="background:white; border-radius:20px; padding:60px; text-align:center; border:2px dashed #e2e8f0; color:#94a3b8; font-weight:700;">
                        🎉 Semua laporan sudah divalidasi!
                    </div>
                @endforelse
            </div>
        </div>

    </main>

    {{-- ===== MODAL: Buat Kampanye ===== --}}
    <div id="modal-create" class="modal-overlay" onclick="if(event.target===this) closeModal()">
        <div style="background:white; border-radius:24px; padding:32px; width:100%; max-width:520px; box-shadow:0 25px 50px rgba(0,0,0,0.2); max-height:90vh; overflow-y:auto;" onclick="event.stopPropagation()">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <h3 style="font-size:1.5rem; font-weight:900; color:#0f172a;">Buat Kampanye Baru</h3>
                <button onclick="closeModal()" style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer; line-height:1;">✕</button>
            </div>
            <form action="{{ route('organizer.campaigns.create') }}" method="POST" style="display:flex; flex-direction:column; gap:16px;">
                @csrf
                <div>
                    <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Judul Kampanye</label>
                    <input type="text" name="title" required placeholder="cth. Bersih Pantai Kenjeran 2026" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan tujuan kampanye..." style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box; resize:vertical;"></textarea>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Tanggal Acara</label>
                        <input type="date" name="event_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Maks Relawan</label>
                        <input type="number" name="max_volunteers" required min="1" placeholder="cth. 50" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Target Capaian</label>
                        <input type="number" name="target_metric" required min="1" placeholder="cth. 500" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Satuan Metrik</label>
                        <input type="text" name="metric_unit" required placeholder="cth. kg sampah" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                    </div>
                </div>
                <div>
                    <label style="display:block; font-size:0.85rem; font-weight:700; color:#374151; margin-bottom:6px;">Berdasarkan Laporan</label>
                    <select name="report_id" style="width:100%; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.9rem; box-sizing:border-box;">
                        <option value="">— Pilih laporan terverifikasi (opsional) —</option>
                        @foreach($verifiedReports as $r)
                            <option value="{{ $r->id }}">[#{{ $r->id }}] {{ $r->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="background:#2563eb; color:white; font-weight:900; font-size:1rem; padding:14px; border-radius:12px; border:none; cursor:pointer; margin-top:4px;">🚀 Buat Kampanye</button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(name) {
            // Sembunyikan semua tab
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.sidebar-link').forEach(b => b.classList.remove('active'));
            // Tampilkan tab yang dipilih
            document.getElementById('tab-' + name).classList.add('active');
            document.getElementById('btn-' + name).classList.add('active');
        }
        function openModal() {
            document.getElementById('modal-create').classList.add('open');
        }
        function closeModal() {
            document.getElementById('modal-create').classList.remove('open');
        }
    </script>
</body>
</html>
