<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog Reward - AksiAlam</title>
    @include('components.head')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <h1 class="text-2xl font-black text-green-600 tracking-tighter">AksiAlam.</h1>
            <nav class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-600 hover:text-green-600">Beranda</a>
                <a href="{{ route('user.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-green-600">Dashboard</a>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl mx-auto px-4 py-10 w-full">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 bg-green-600 rounded-3xl p-8 text-white shadow-lg">
            <div>
                <h2 class="text-3xl font-black mb-2">Katalog Reward</h2>
                <p class="text-green-100 font-medium">Tukarkan EXP Points Anda dengan merchandise atau voucher ramah lingkungan.</p>
            </div>
            <div class="mt-6 md:mt-0 text-center bg-white/20 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20">
                <p class="text-sm font-bold text-green-100 mb-1">Total EXP Anda</p>
                <p class="text-4xl font-black">{{ $user->exp_points }} <span class="text-lg">XP</span></p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($rewards as $reward)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition">
                    <img src="{{ $reward->image_url ?? 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=600' }}" alt="{{ $reward->name }}" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg leading-tight">{{ $reward->name }}</h3>
                        </div>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $reward->description }}</p>
                        
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Harga</p>
                                <p class="font-black text-green-600 text-lg">{{ $reward->points_required }} XP</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase text-right">Stok</p>
                                <p class="font-bold text-slate-700 text-right">{{ $reward->stock }}</p>
                            </div>
                        </div>

                        <form action="{{ route('user.rewards.redeem', $reward->id) }}" method="POST" class="mt-4">
                            @csrf
                            @if($reward->stock > 0 && $user->exp_points >= $reward->points_required)
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-xl transition">
                                    Tukar Sekarang
                                </button>
                            @elseif($reward->stock <= 0)
                                <button type="button" disabled class="w-full bg-slate-200 text-slate-500 font-bold py-2 rounded-xl cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @else
                                <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-2 rounded-xl cursor-not-allowed">
                                    XP Kurang
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl">
                    <p class="font-bold text-lg">Belum ada reward yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>
