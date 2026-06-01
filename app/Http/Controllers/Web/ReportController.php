<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    private $reportRepository;

    // Inject Repository yang sama dengan yang dipakai di API
    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
        $category = $request->input('category');

        // Data Utama (Hasil Search)
        $reports = $this->reportRepository->searchReports($query, $category);

        // Data Statistik (Impact Metrics)
        $stats = [
            'reports' => $this->reportRepository->getTotalValidatedReports(),
            'volunteers' => app(\App\Repositories\Interfaces\CampaignRepositoryInterface::class)->getTotalVolunteers(),
            'impact' => app(\App\Repositories\Interfaces\CampaignRepositoryInterface::class)->getTotalImpactMetric(),
        ];

        // Data Leaderboard (Top 5)
        $leaderboard = app(\App\Repositories\Interfaces\CampaignRepositoryInterface::class)->getTopVolunteers(5);

        // Data Kampanye untuk Pin di Peta
        $campaignsForMap = \App\Models\Campaign::select('id', 'title', 'location_name', 'latitude', 'longitude', 'status', 'event_date')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Kampanye Aktif (status = open)
        $activeCampaigns = \App\Models\Campaign::with('organizer')
            ->where('status', 'open')
            ->latest()
            ->get();

        // Kampanye Lainnya (finished / closed)
        $otherCampaigns = \App\Models\Campaign::with('organizer')
            ->whereIn('status', ['finished', 'closed'])
            ->latest()
            ->get();

        // Berita Ekologi Indonesia dari NewsAPI (di-cache 6 jam)
        $ecoNews = \Illuminate\Support\Facades\Cache::remember('eco_news', 60 * 360, function () {
            try {
                $apiKey = env('NEWS_API_KEY');
                // Gunakan keyword ekologi dengan pencarian frase pasti agar spesifik berbahasa Indonesia
                $query = urlencode('"lingkungan hidup" OR "kerusakan lingkungan" OR "hutan indonesia" OR "sampah plastik"');
                // Hapus filter domain agar cakupan pencarian lebih luas ke berbagai media
                $url = "https://newsapi.org/v2/everything?q={$query}&sortBy=publishedAt&pageSize=9&apiKey={$apiKey}";
                
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $articles = $data['articles'] ?? [];
                    // Filter artikel yang tidak valid
                    return array_filter($articles, fn($a) => !empty($a['title']) && $a['title'] !== '[Removed]' && !empty($a['urlToImage']));
                }
            } catch (\Exception $e) {
                // Fallback: return empty array jika API gagal
            }
            return [];
        });

        // Ambil maksimal 8 laporan untuk ditampilkan di carousel
        $reports = $reports->take(8);

        return view('reports.index', compact('reports', 'stats', 'leaderboard', 'campaignsForMap', 'activeCampaigns', 'otherCampaigns', 'ecoNews'));
    }

    // Menambahkan method ini di bawah method index()
    public function create()
    {
        // Fetch existing reports to show as markers on the map
        $existingReports = \App\Models\Report::select('id', 'title', 'location_name', 'latitude', 'longitude', 'status', 'category')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        return view('reports.create', compact('existingReports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:sampah,fasilitas,flora_fauna',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi Foto
        ]);

        // Hanya gunakan field yang sudah divalidasi
        $data = collect($validated)->except('image')->toArray();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        // Jika image_url kosong, biarkan seeder atau default yang mengisi
        \App\Models\Report::create($data);

        return redirect()->route('user.dashboard')->with('success', 'Laporan berhasil dikirim! Terima kasih telah peduli. 🌿');
    }
}