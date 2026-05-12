@props(['report'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col cursor-pointer">
    
    <div class="h-48 w-full overflow-hidden relative bg-slate-200">
        @php
            // Memanggil gambar asli dari database. 
            // Jika kosong (NULL), gunakan gambar default krisis lingkungan yang dramatis.
            $imgUrl = $report->image_url ?? 'https://images.unsplash.com/photo-1596464716127-f2a82984de30?auto=format&fit=crop&w=600&q=80'; // Default photo of crisis
        @endphp
        
        <img src="{{ $imgUrl }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $report->title }}">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

        <div class="absolute top-3 left-3 px-3 py-1 text-xs font-black rounded-full shadow-md uppercase tracking-wider
            @if($report->status === 'pending') bg-amber-400 text-amber-900
            @elseif($report->status === 'verified') bg-blue-500 text-white
            @else bg-emerald-500 text-white @endif">
            {{ $report->status }}
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col">
        <div class="flex justify-between items-center mb-3">
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ str_replace('_', ' ', $report->category) }}</span>
            <span class="text-xs text-slate-500 font-medium">{{ $report->created_at->format('d M Y') }}</span>
        </div>
        
        <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors">{{ $report->title }}</h3>
        <p class="text-slate-500 text-sm mb-4 line-clamp-2 flex-1">{{ $report->description }}</p>
        
        <div class="flex items-center text-sm text-slate-500 pt-4 border-t border-slate-100">
            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="truncate font-medium">{{ $report->location_name }}</span>
        </div>
    </div>
</div>