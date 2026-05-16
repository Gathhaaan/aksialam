<!DOCTYPE html>
<html lang="id">
<head>
    <title>Masuk - AksiAlam</title>
    @include('components.head')
</head>
<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto py-12 px-6">
        <a href="{{ route('home') }}" class="text-sm font-bold text-slate-400 hover:text-green-600">← Kembali ke Beranda</a>
        
        <div class="mt-8 bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <img src="{{ $campaign->report->image_url }}" class="w-full h-80 object-cover">
            <div class="p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black uppercase">Aksi Berlangsung</span>
                        <h1 class="text-3xl font-black mt-4 text-slate-900">{{ $campaign->title }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-400">Target Dampak</p>
                        <h4 class="text-xl font-black text-green-600">{{ $campaign->target_metric }} {{ $campaign->metric_unit }}</h4>
                    </div>
                </div>

                <div class="mt-8 grid md:grid-cols-2 gap-8 text-slate-600 leading-relaxed">
                    <div>
                        <h5 class="font-bold text-slate-900 mb-2">Lokasi & Waktu</h5>
                        <p>📍 {{ $campaign->report->location_name }}</p>
                        <p>📅 {{ \Carbon\Carbon::parse($campaign->event_date)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 mb-2">Penyelenggara</h5>
                        <p>🏢 {{ $campaign->organizer->name }} (Komunitas Mitra)</p>
                    </div>
                </div>

                <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                    <h5 class="font-bold text-center mb-4">Siap untuk berkontribusi?</h5>
                    <form action="{{ route('campaigns.join', $campaign->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/20">Daftar Jadi Relawan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>