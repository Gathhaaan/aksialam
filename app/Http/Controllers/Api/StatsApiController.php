<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

/**
 * API Controller untuk Statistik dan Leaderboard.
 * Endpoint publik yang dilindungi oleh API Key.
 */
class StatsApiController extends Controller
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
     * GET /api/v1/stats
     * Menampilkan statistik global platform AksiAlam.
     * Dilindungi oleh API Key (header X-API-KEY).
     */
    public function index()
    {
        $reportCounts = $this->reportRepository->getReportCounts();

        return response()->json([
            'success' => true,
            'message' => 'Statistik platform AksiAlam',
            'data'    => [
                'reports' => $reportCounts,
                'validated_reports' => $this->reportRepository->getTotalValidatedReports(),
                'total_volunteers'  => $this->campaignRepository->getTotalVolunteers(),
                'total_impact'      => $this->campaignRepository->getTotalImpactMetric(),
                'total_campaigns'   => \App\Models\Campaign::count(),
            ],
        ], 200);
    }

    /**
     * GET /api/v1/leaderboard
     * Menampilkan top relawan berdasarkan Experience Points.
     * Dilindungi oleh API Key (header X-API-KEY).
     */
    public function leaderboard()
    {
        $topVolunteers = $this->campaignRepository->getTopVolunteers(10);

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard relawan AksiAlam',
            'data'    => $topVolunteers->map(function ($user, $index) {
                return [
                    'rank'       => $index + 1,
                    'name'       => $user->name,
                    'exp_points' => $user->exp_points,
                ];
            }),
        ], 200);
    }
}
