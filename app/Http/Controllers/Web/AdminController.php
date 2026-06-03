<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

class AdminController extends Controller
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
        // Statistik global sistem (via repository)
        $reportCounts = $this->reportRepository->getReportCounts();
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_organizers'  => User::where('role', 'organizer')->count(),
            'total_reports'     => $reportCounts['total'],
            'pending_reports'   => $reportCounts['pending'],
            'verified_reports'  => $reportCounts['verified'],
            'total_campaigns'   => \App\Models\Campaign::count(),
            'open_campaigns'    => \App\Models\Campaign::where('status', 'open')->count(),
            'total_volunteers'  => \DB::table('campaign_user')->count(),
        ];

        // Laporan berhalaman
        $recentReports = \App\Models\Report::with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'reports_page');

        // User berhalaman
        $recentUsers = User::latest()->paginate(10, ['*'], 'users_page');

        // Kampanye berhalaman
        $allCampaigns = \App\Models\Campaign::with(['organizer', 'report'])->latest()->paginate(10, ['*'], 'campaigns_page');

        return view('admin.dashboard', compact(
            'stats', 'recentReports', 'recentUsers', 'allCampaigns'
        ));
    }

    // Ubah status laporan (admin bisa approve/reject) — via repository
    public function updateReportStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,verified,resolved,rejected']);
        $this->reportRepository->updateReportStatus($id, $request->status);

        return back()->with('success', 'Status laporan berhasil diubah!');
    }

    // Hapus user (dengan proteksi self-delete)
    public function deleteUser($id)
    {
        if ($id == Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // Ubah role user
    public function changeUserRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:user,organizer,admin']);

        if ($id == Auth::id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri!');
        }

        User::findOrFail($id)->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diubah!');
    }
}
