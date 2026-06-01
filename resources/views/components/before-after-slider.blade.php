@props(['before' => '', 'after' => ''])

<div class="relative w-full max-w-2xl mx-auto h-64 sm:h-96 rounded-xl overflow-hidden shadow-sm" x-data="{ sliderPos: 50 }">
    <img src="{{ $after }}" alt="Kondisi Sesudah" class="absolute inset-0 w-full h-full object-cover rounded-xl" />

    <img src="{{ $before }}" alt="Kondisi Sebelum" class="absolute inset-0 w-full h-full object-cover rounded-xl" :style="`clip-path: polygon(0 0, ${sliderPos}% 0, ${sliderPos}% 100%, 0 100%);`" />

    <input type="range" min="0" max="100" x-model="sliderPos" class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-10" />

    <div class="absolute top-0 bottom-0 w-1 bg-white cursor-ew-resize z-0 pointer-events-none" :style="`left: ${sliderPos}%`">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white rounded-full shadow-md flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
        </div>
    </div>
</div>