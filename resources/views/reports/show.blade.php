<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Laporan - AksiAlam</title>
    @include('components.head')
</head>
<body class="bg-slate-50 min-h-screen pt-10 pb-20">
    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-green-600 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($report->image_url)
                <img src="{{ $report->image_url }}" alt="{{ $report->title }}" class="w-full h-80 object-cover">
            @else
                <div class="w-full h-80 bg-slate-200 flex items-center justify-center">
                    <span class="text-slate-400 font-bold">Tidak ada foto</span>
                </div>
            @endif

            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold uppercase tracking-wider">
                        Kategori: {{ ucwords(str_replace('_', ' ', $report->category)) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $report->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $report->status == 'verified' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $report->status == 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $report->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        Status: {{ $report->status }}
                    </span>
                </div>

                <h1 class="text-3xl font-black text-slate-900 mb-2">{{ $report->title }}</h1>
                
                <div class="flex items-center text-slate-500 mb-8">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">{{ $report->location_name }}</span>
                </div>

                <div class="prose prose-slate max-w-none mb-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Deskripsi Kerusakan</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $report->description }}</p>
                </div>

                <div class="border-t border-slate-100 pt-6 mt-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-500">
                            {{ $report->user ? substr($report->user->name, 0, 2) : 'NN' }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Dilaporkan oleh {{ $report->user->name ?? 'Anonim' }}</p>
                            <p class="text-xs text-slate-500">{{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
