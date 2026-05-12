<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ReportRepositoryInterface;

class ReportApiController extends Controller
{
    private $reportRepository;

    // Inject Repository melalui Constructor
    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
    {
        $reports = $this->reportRepository->getAllReports();
        return response()->json(['message' => 'Success', 'data' => $reports], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:sampah,fasilitas,flora_fauna',
            'location_name' => 'required|string',
            // image_before_path bisa ditambahkan logic upload file nantinya
        ]);

        $data = $request->all();
        // Mengambil ID user yang sedang login dari token JWT
        $data['user_id'] = auth('api')->user()->id; 
        
        $report = $this->reportRepository->createReport($data);

        return response()->json(['message' => 'Laporan berhasil dibuat', 'data' => $report], 201);
    }
}