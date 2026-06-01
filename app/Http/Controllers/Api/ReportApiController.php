<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ReportRepositoryInterface;

/**
 * API Controller untuk resource Report (Laporan Kerusakan Alam).
 * Mendukung full CRUD dengan autentikasi JWT.
 * Repository Pattern digunakan untuk semua operasi data.
 */
class ReportApiController extends Controller
{
    private $reportRepository;

    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * GET /api/v1/reports
     * Menampilkan semua laporan. Mendukung filter berdasarkan status dan kategori.
     */
    public function index(Request $request)
    {
        // Jika ada query search/filter
        if ($request->has('q') || $request->has('category')) {
            $reports = $this->reportRepository->searchReports(
                $request->query('q'),
                $request->query('category')
            );
        } elseif ($request->has('status')) {
            $reports = $this->reportRepository->getReportsByStatus($request->query('status'));
        } else {
            $reports = $this->reportRepository->getAllReports();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar laporan berhasil diambil',
            'data'    => $reports,
            'total'   => $reports->count(),
        ], 200);
    }

    /**
     * GET /api/v1/reports/{id}
     * Menampilkan detail laporan berdasarkan ID.
     */
    public function show($id)
    {
        $report = $this->reportRepository->getReportById($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail laporan',
            'data'    => $report,
        ], 200);
    }

    /**
     * POST /api/v1/reports
     * Membuat laporan baru. Memerlukan JWT token.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'category'      => 'required|in:sampah,fasilitas,flora_fauna',
            'location_name' => 'required|string',
            'image_url'     => 'nullable|url',
        ]);

        $data = $request->only(['title', 'description', 'category', 'location_name', 'image_url']);
        $data['user_id'] = auth('api')->user()->id;
        $data['status'] = 'pending';

        $report = $this->reportRepository->createReport($data);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data'    => $report,
        ], 201);
    }

    /**
     * PUT /api/v1/reports/{id}
     * Mengupdate laporan. Hanya pemilik laporan yang bisa mengupdate.
     */
    public function update(Request $request, $id)
    {
        $report = $this->reportRepository->getReportById($id);

        // Authorization: hanya pemilik laporan
        if ($report->user_id !== auth('api')->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah laporan ini.',
            ], 403);
        }

        $request->validate([
            'title'         => 'sometimes|string|max:255',
            'description'   => 'sometimes|string',
            'category'      => 'sometimes|in:sampah,fasilitas,flora_fauna',
            'location_name' => 'sometimes|string',
        ]);

        $report->update($request->only(['title', 'description', 'category', 'location_name']));

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data'    => $report->fresh(),
        ], 200);
    }

    /**
     * DELETE /api/v1/reports/{id}
     * Menghapus laporan. Hanya pemilik atau admin yang bisa menghapus.
     */
    public function destroy($id)
    {
        $report = $this->reportRepository->getReportById($id);
        $user = auth('api')->user();

        // Authorization: pemilik laporan atau admin
        if ($report->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus laporan ini.',
            ], 403);
        }

        $this->reportRepository->deleteReport($id);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus',
        ], 200);
    }
}