<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

class OrganizerController extends Controller
{
    protected $reportRepository;
    protected $campaignRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        CampaignRepositoryInterface $campaignRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Campaign yang dikelola oleh organizer ini (via repository)
        $myCampaigns = $this->campaignRepository->getCampaignsByOrganizer($user->id);

        // Laporan yang menunggu validasi (status pending)
        $pendingReports = $this->reportRepository->getReportsByStatus('pending', 10);

        // Laporan yang sudah diverifikasi (untuk dropdown buat kampanye)
        $verifiedReports = $this->reportRepository->getReportsByStatus('verified');

        // Statistik campaign organizer ini
        $stats = [
            'total_campaigns' => $myCampaigns->count(),
            'open_campaigns'  => $myCampaigns->where('status', 'open')->count(),
            'done_campaigns'  => $myCampaigns->where('status', 'finished')->count(),
            'total_volunteers'=> \DB::table('campaign_user')
                ->whereIn('campaign_id', $myCampaigns->pluck('id'))
                ->count(),
        ];

        return view('organizer.dashboard', compact(
            'user', 'myCampaigns', 'pendingReports', 'verifiedReports', 'stats'
        ));
    }

    // Validasi laporan → ubah status ke 'verified' (via repository)
    public function verifyReport($id)
    {
        $this->reportRepository->updateReportStatus($id, 'verified');

        return back()->with('success', 'Laporan berhasil diverifikasi!');
    }

    // Buat Campaign baru dari laporan yang sudah diverifikasi (via repository)
    public function createCampaign(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'event_date'      => 'required|date|after:today',
            'max_volunteers'  => 'required|integer|min:1',
            'target_metric'   => 'required|numeric|min:0',
            'metric_unit'     => 'required|string|max:50',
            'report_id'       => 'nullable|exists:reports,id',
        ]);

        $this->campaignRepository->createCampaign([
            'title'           => $request->title,
            'description'     => $request->description,
            'event_date'      => $request->event_date,
            'max_volunteers'  => $request->max_volunteers,
            'target_metric'   => $request->target_metric,
            'metric_unit'     => $request->metric_unit,
            'report_id'       => $request->report_id,
            'organizer_id'    => Auth::id(),
            'status'          => 'open',
        ]);

        return back()->with('success', 'Kampanye berhasil dibuat!');
    }
}
