@props(['report'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col cursor-pointer">
    <div class="h-48 w-full overflow-hidden relative bg-slate-200">
        @php
            // Memastikan URL valid. Jika kosong, pakai gambar krisis umum.
            $imgUrl = $report->image_url ?? 'https://images.unsplash.com/photo-1596464716127-f2a82984de30?q=80&w=1000';
        @endphp
        
        <!-- Script onerror: Jika link internet mati, otomatis ganti ke gambar cadangan yang stabil -->
        <img src="{{ $imgUrl }}" 
             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1621451537084-482c73073e0f?q=80&w=1000';"
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
             alt="{{ $report->title }}">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        <div class="absolute top-3 left-3 px-3 py-1 text-xs font-black rounded-full shadow-md uppercase tracking-wider
            @if($report->status === 'pending') bg-amber-400 text-amber-900
            @elseif($report->status === 'verified') bg-blue-500 text-white
            @else bg-emerald-500 text-white @endif">
            {{ $report->status }}
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col">
        <div class="flex justify-between items-center mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">
            <span>{{ str_replace('_', ' ', $report->category) }}</span>
            <span>{{ $report->created_at->format('d M Y') }}</span>
        </div>
        <h3 class="text-base font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">{{ $report->title }}</h3>
        <p class="text-slate-500 text-xs mb-4 line-clamp-2 flex-1">{{ $report->description }}</p>
        <div class="flex items-center text-[10px] text-slate-500 pt-4 border-t border-slate-100">
            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            <span class="truncate font-semibold">{{ $report->location_name }}</span>
        </div>
    </div>
</div>