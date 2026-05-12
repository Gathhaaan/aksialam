<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:sampah,fasilitas,flora_fauna',
            'location_name' => 'required|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'pending'; // Default status

        $this->reportRepository->createReport($data);

        // Kembali ke beranda setelah lapor
        return redirect('/')->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi.');
    }
}