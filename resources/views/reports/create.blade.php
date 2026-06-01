<!DOCTYPE html>
<html lang="id">
<head>
    <title>Buat Laporan - AksiAlam</title>
    
    @include('components.head')
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class="bg-slate-50 min-h-screen pt-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Laporkan Kondisi Alam</h2>
        <p class="text-slate-500 mb-6">Bantu komunitas mengetahui titik mana yang membutuhkan aksi swadaya segera.</p>

        <form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Judul Masalah</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Tumpukan sampah di Pos 1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                    <option value="sampah" {{ old('category') == 'sampah' ? 'selected' : '' }}>Penumpukan Sampah</option>
                    <option value="fasilitas" {{ old('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas Rusak</option>
                    <option value="flora_fauna" {{ old('category') == 'flora_fauna' ? 'selected' : '' }}>Ancaman Flora/Fauna</option>
                </select>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Kejadian (Nama Tempat)</label>
                <input type="text" name="location_name" value="{{ old('location_name') }}" placeholder="Contoh: Gunung Bromo, Jawa Timur" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                @error('location_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Titik Koordinat (Peta)</label>
                
                <div class="mb-3">
                    <input type="text" id="coordinates_input" placeholder="Contoh: -7.289824, 112.727714" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm font-mono">
                </div>

                <div id="map" class="w-full h-64 rounded-lg border border-slate-300 z-10 mb-2"></div>
                <p class="text-xs text-slate-400">Geser pin biru, klik pada peta, atau *paste* koordinat (Latitude, Longitude) dari Google Maps ke kolom atas.</p>
                
                <!-- Hidden inputs untuk backend Laravel -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '-0.789275') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '113.921327') }}">
                
                @error('latitude') <p class="text-red-500 text-xs mt-1">Koordinat peta wajib diisi dengan benar.</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Foto Bukti Kondisi</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Detail</label>
                <textarea name="description" rows="4" placeholder="Jelaskan secara detail kondisi di lapangan..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('user.dashboard') }}" class="text-slate-500 hover:text-slate-700 font-medium">Batal</a>
                <button type="submit" class="bg-green-600 text-white font-medium py-2 px-6 rounded-lg hover:bg-green-700 transition">Kirim Laporan</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');
            var coordInput = document.getElementById('coordinates_input');
            
            var lat = parseFloat(latInput.value) || -0.789275;
            var lng = parseFloat(lngInput.value) || 113.921327;
            
            // Set initial text
            coordInput.value = lat + ", " + lng;
            
            var map = L.map('map').setView([lat, lng], 5);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);

            // ==========================================
            // Tampilkan Data Laporan Asli di Peta
            // ==========================================
            var existingReports = {!! json_encode($existingReports ?? []) !!};
            
            // Ikon khusus untuk marker laporan (titik merah)
            var reportIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#ef4444;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 6px rgba(0,0,0,0.5);'></div>",
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            existingReports.forEach(function(report) {
                if (report.latitude && report.longitude) {
                    var statusColor = report.status === 'approved' ? 'text-green-600 bg-green-50' : 'text-yellow-600 bg-yellow-50';
                    var statusText = report.status === 'approved' ? '✅ Terverifikasi' : '⏳ Pending';
                    
                    var popupContent = `
                        <div style="font-family: inherit; min-width: 150px;">
                            <p style="font-weight: bold; margin: 0 0 4px 0; color: #1e293b;">${report.title}</p>
                            <p style="font-size: 11px; margin: 0 0 8px 0; color: #64748b;">📍 ${report.location_name}</p>
                            <span class="${statusColor}" style="font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold;">${statusText}</span>
                        </div>
                    `;
                    
                    L.marker([parseFloat(report.latitude), parseFloat(report.longitude)], {icon: reportIcon})
                        .addTo(map)
                        .bindPopup(popupContent);
                }
            });
            // ==========================================
            function updateFromMap(lat, lng) {
                // Update hidden inputs
                latInput.value = lat;
                lngInput.value = lng;
                // Update visible text input
                coordInput.value = lat + ", " + lng;
                // Update marker
                marker.setLatLng([lat, lng]);
            }

            // Saat marker digeser
            marker.on('dragend', function (e) {
                var position = marker.getLatLng();
                updateFromMap(position.lat, position.lng);
            });

            // Saat peta diklik
            map.on('click', function(e) {
                updateFromMap(e.latlng.lat, e.latlng.lng);
            });

            // Saat input di ketik/paste manual
            coordInput.addEventListener('input', function() {
                var val = this.value.trim();
                var parts = val.split(',');
                
                if (parts.length >= 2) {
                    var newLat = parseFloat(parts[0].trim());
                    var newLng = parseFloat(parts[1].trim());
                    
                    if (!isNaN(newLat) && !isNaN(newLng)) {
                        // Update hidden inputs for backend
                        latInput.value = newLat;
                        lngInput.value = newLng;
                        
                        // Pindahkan marker dan fokus peta dengan animasi zoom in
                        marker.setLatLng([newLat, newLng]);
                        map.flyTo([newLat, newLng], 15, {
                            animate: true,
                            duration: 1.5
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>