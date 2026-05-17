@props(['report'])
<a href="{{ $report->campaign ? route('campaigns.show', $report->campaign->id) : '#' }}" class="block group">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full">
        <div class="h-48 w-full overflow-hidden relative bg-slate-200">
             <img src="{{ $report->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
             </div>
        <div class="p-5">
            <h3 class="font-bold group-hover:text-green-600">{{ $report->title }}</h3>
            @if($report->campaign)
                <p class="mt-4 text-xs font-black text-green-600 uppercase tracking-tighter">Lihat Detail Aksi →</p>
            @endif
        </div>
    </div>
</a>