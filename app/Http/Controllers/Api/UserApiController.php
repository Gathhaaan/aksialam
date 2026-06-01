<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

/**
 * API Controller untuk resource User (Profil & Data Pengguna).
 * Memerlukan autentikasi JWT untuk semua endpoint.
 */
class UserApiController extends Controller
{
    private $reportRepository;
    private $campaignRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        CampaignRepositoryInterface $campaignRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * GET /api/v1/user/profile
     * Menampilkan profil user yang sedang login.
     */
    public function profile()
    {
        $user = auth('api')->user();

        // Hitung peringkat XP
        $rank = \App\Models\User::where('role', 'user')
            ->where('exp_points', '>', $user->exp_points)
            ->count() + 1;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'exp_points' => $user->exp_points,
                'rank'       => $rank,
                'created_at' => $user->created_at,
            ],
        ], 200);
    }

    /**
     * GET /api/v1/user/reports
     * Menampilkan semua laporan milik user yang sedang login.
     */
    public function myReports()
    {
        $reports = $this->reportRepository->getReportsByUser(auth('api')->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Laporan milik Anda',
            'data'    => $reports,
            'total'   => $reports->count(),
        ], 200);
    }

    /**
     * GET /api/v1/user/campaigns
     * Menampilkan semua campaign yang diikuti user yang sedang login.
     */
    public function myCampaigns()
    {
        $user = auth('api')->user();
        $campaigns = $user->joinedCampaigns()
            ->with(['organizer', 'report'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Kampanye yang Anda ikuti',
            'data'    => $campaigns,
            'total'   => $campaigns->count(),
        ], 200);
    }
}
