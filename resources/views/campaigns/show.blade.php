<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $campaign->title }} - AksiAlam</title>
    @include('components.head')
</head>
<body class="bg-slate-50">
    <div class="max-w-4xl mx-auto py-12 px-6">
        <a href="{{ route('home') }}" class="text-sm font-bold text-slate-400 hover:text-green-600">← Kembali ke Beranda</a>
        
        <div class="mt-8 bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            @if($campaign->report?->image_url)
                <img src="{{ $campaign->report->image_url }}" class="w-full h-80 object-cover" alt="{{ $campaign->title }}">
            @else
                <div class="w-full h-80 bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center">
                    <span class="text-7xl">🌿</span>
                </div>
            @endif
            <div class="p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="bg-{{ $campaign->status === 'open' ? 'green' : ($campaign->status === 'finished' ? 'blue' : 'slate') }}-100 text-{{ $campaign->status === 'open' ? 'green' : ($campaign->status === 'finished' ? 'blue' : 'slate') }}-700 px-3 py-1 rounded-full text-xs font-black uppercase">{{ ucfirst($campaign->status) }}</span>
                        <h1 class="text-3xl font-black mt-4 text-slate-900">{{ $campaign->title }}</h1>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-400">Target Dampak</p>
                        <h4 class="text-xl font-black text-green-600">{{ $campaign->target_metric }} {{ $campaign->metric_unit }}</h4>
                    </div>
                </div>

                @if($campaign->description)
                    <p class="mt-6 text-slate-600 leading-relaxed">{{ $campaign->description }}</p>
                @endif

                <div class="mt-8 grid md:grid-cols-2 gap-8 text-slate-600 leading-relaxed">
                    <div>
                        <h5 class="font-bold text-slate-900 mb-2">Lokasi & Waktu</h5>
                        <p>📍 {{ $campaign->report?->location_name ?? 'Belum ditentukan' }}</p>
                        <p>📅 {{ \Carbon\Carbon::parse($campaign->event_date)->format('d F Y') }}</p>
                        <p>👥 {{ $campaign->volunteers()->count() }}/{{ $campaign->max_volunteers }} Relawan</p>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 mb-2">Penyelenggara</h5>
                        <p>🏢 {{ $campaign->organizer->name }} (Komunitas Mitra)</p>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->role === 'organizer' && auth()->id() === $campaign->organizer_id)
                        <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                            <h5 class="font-black text-xl text-slate-900 mb-2">QR Code Absensi Relawan</h5>
                            <p class="text-sm text-slate-500 mb-6">Tunjukkan QR Code ini kepada relawan di lapangan agar mereka dapat memindainya untuk check-in dan mendapatkan +50 XP.</p>
                            
                            <div class="inline-block p-4 bg-white rounded-xl shadow-sm border border-slate-100 mb-4">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('campaigns.checkin', $campaign->id)) }}" alt="QR Check-in" class="w-48 h-48">
                            </div>
                            
                            <p class="text-xs text-slate-400">Atau berikan link manual: <a href="{{ route('campaigns.checkin', $campaign->id) }}" class="text-green-600 underline">{{ route('campaigns.checkin', $campaign->id) }}</a></p>
                        </div>

                        <!-- Daftar Relawan (Hanya untuk Organizer) -->
                        <div class="mt-8 text-left bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h5 class="font-bold text-lg text-slate-900 mb-4">Daftar Kehadiran Relawan</h5>
                            @if($campaign->volunteers->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-slate-600">
                                        <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                                            <tr>
                                                <th class="px-4 py-3 rounded-tl-lg">Nama Relawan</th>
                                                <th class="px-4 py-3">Email</th>
                                                <th class="px-4 py-3 rounded-tr-lg text-center">Status Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($campaign->volunteers as $volunteer)
                                                <tr class="border-b border-slate-50 last:border-0">
                                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $volunteer->name }}</td>
                                                    <td class="px-4 py-3">{{ $volunteer->email }}</td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if($volunteer->pivot->attendance === 'attended')
                                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Hadir ✅</span>
                                                        @else
                                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Belum Hadir</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-slate-500 text-center py-4">Belum ada relawan yang mendaftar ke aksi ini.</p>
                            @endif
                        </div>
                    @elseif($campaign->status === 'open')
                        @php
                            $isJoined = $campaign->volunteers()->where('user_id', auth()->id())->exists();
                            $hasAttended = $isJoined ? $campaign->volunteers()->where('user_id', auth()->id())->first()->pivot->attendance === 'attended' : false;
                        @endphp
                        
                        @if($isJoined && !$hasAttended)
                            <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-center">
                                <h5 class="font-bold mb-4">Kamu sudah terdaftar di aksi ini!</h5>
                                <p class="text-sm text-slate-600 mb-4">Saat kamu tiba di lokasi, minta barcode absensi dari Organizer dan pindai untuk mendapatkan XP.</p>
                                
                                <button id="btn-scan" class="inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 mb-4">
                                    📷 Buka Kamera Scanner
                                </button>

                                <div id="reader-container" class="hidden max-w-sm mx-auto overflow-hidden rounded-xl border border-slate-200">
                                    <div id="reader" width="100%"></div>
                                    <button id="btn-close-scan" class="w-full bg-slate-200 text-slate-700 font-bold py-2 hover:bg-slate-300 transition">Tutup Kamera</button>
                                </div>
                            </div>
                        @elseif($hasAttended)
                            <div class="mt-10 p-6 bg-green-50 rounded-2xl border border-green-200 text-center">
                                <span class="text-4xl block mb-2">✅</span>
                                <h5 class="font-bold text-green-700">Kamu sudah Check-in!</h5>
                                <p class="text-sm text-green-600 mt-1">Terima kasih atas dedikasi dan kontribusimu di lapangan.</p>
                            </div>
                        @else
                            <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                                <h5 class="font-bold text-center mb-4">Siap untuk berkontribusi?</h5>
                                <form action="{{ route('campaigns.join', $campaign->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/20">Daftar Jadi Relawan Sekarang</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="mt-10 p-6 bg-slate-100 rounded-2xl text-center">
                            <p class="font-bold text-slate-500">Kampanye ini sudah {{ $campaign->status === 'finished' ? 'selesai' : 'ditutup' }}.</p>
                        </div>
                    @endif
                @else
                    <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                        <p class="font-bold text-slate-600 mb-3">Login untuk mendaftar aksi ini.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-slate-900 text-white font-bold py-2 px-6 rounded-lg hover:bg-slate-800">Login Sekarang</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Script QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnScan = document.getElementById('btn-scan');
            const btnClose = document.getElementById('btn-close-scan');
            const container = document.getElementById('reader-container');
            
            if(btnScan) {
                let html5QrcodeScanner = null;

                btnScan.addEventListener('click', () => {
                    container.classList.remove('hidden');
                    btnScan.classList.add('hidden');
                    
                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader",
                        { fps: 10, qrbox: {width: 250, height: 250} },
                        /* verbose= */ false
                    );
                    
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                });

                btnClose.addEventListener('click', () => {
                    if(html5QrcodeScanner) {
                        html5QrcodeScanner.clear();
                    }
                    container.classList.add('hidden');
                    btnScan.classList.remove('hidden');
                });

                function onScanSuccess(decodedText, decodedResult) {
                    // Jika hasil scan adalah URL dari aplikasi kita, redirect ke sana
                    if(decodedText.includes('/campaign/') && decodedText.includes('/checkin')) {
                        // Hentikan scanner
                        html5QrcodeScanner.clear();
                        // Redirect browser
                        window.location.href = decodedText;
                    } else {
                        alert("QR Code tidak valid untuk absensi kampanye ini.");
                    }
                }

                function onScanFailure(error) {
                    // Abaikan pesan error background yang berjalan terus-menerus
                }
            }
        });
    </script>
</body>
</html>