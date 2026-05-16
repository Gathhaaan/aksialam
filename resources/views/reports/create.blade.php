<!DOCTYPE html>
<html lang="id">
<head>
    <title>Beranda - AksiAlam</title>
    
    @include('components.head')

    </head>
<body class="bg-slate-50 min-h-screen pt-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Laporkan Kondisi Alam</h2>
        <p class="text-slate-500 mb-6">Bantu komunitas mengetahui titik mana yang membutuhkan aksi swadaya segera.</p>

        <form action="{{ route('reports.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Judul Masalah</label>
                <input type="text" name="title" placeholder="Contoh: Tumpukan sampah di Pos 1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                    <option value="sampah">Penumpukan Sampah</option>
                    <option value="fasilitas">Fasilitas Rusak</option>
                    <option value="flora_fauna">Ancaman Flora/Fauna</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Kejadian</label>
                <input type="text" name="location_name" placeholder="Contoh: Gunung Bromo, Jawa Timur" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Detail</label>
                <textarea name="description" rows="4" placeholder="Jelaskan secara detail kondisi di lapangan..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-green-500 focus:border-green-500" required></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="/" class="text-slate-500 hover:text-slate-700 font-medium">Batal</a>
                <button type="submit" class="bg-green-600 text-white font-medium py-2 px-6 rounded-lg hover:bg-green-700 transition">Kirim Laporan</button>
            </div>
        </form>
    </div>
</body>
</html>