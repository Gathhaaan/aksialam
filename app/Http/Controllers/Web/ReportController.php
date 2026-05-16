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

        return view('reports.index', compact('reports', 'stats', 'leaderboard'));
    }

    // Menambahkan method ini di bawah method index()
    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'category' => 'required',
            'location_name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi Foto
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        // Jika image_url kosong, biarkan seeder atau default yang mengisi
        \App\Models\Report::create($data);

        return redirect()->route('home')->with('success', 'Laporan berhasil dikirim!');
    }
}