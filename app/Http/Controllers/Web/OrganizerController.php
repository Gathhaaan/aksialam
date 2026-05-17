<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Campaign;
use App\Models\User;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        // Pastikan hanya role 'organizer' yang bisa akses
        if (Auth::user()->role !== 'organizer') {
            return $this->redirectByRole();
        }

        $user = Auth::user();

        // Campaign yang dikelola oleh organizer ini
        $myCampaigns = Campaign::with(['volunteers', 'report'])
            ->where('organizer_id', $user->id)
            ->latest()->get();

        // Laporan yang menunggu validasi (status pending)
        $pendingReports = Report::where('status', 'pending')->latest()->take(10)->get();

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
            'user', 'myCampaigns', 'pendingReports', 'stats'
        ));
    }

    // Validasi laporan → ubah status ke 'verified'
    public function verifyReport($id)
    {
        if (Auth::user()->role !== 'organizer') abort(403);

        $report = Report::findOrFail($id);
        $report->update(['status' => 'verified']);

        return back()->with('success', 'Laporan berhasil diverifikasi!');
    }

    // Buat Campaign baru dari laporan yang sudah diverifikasi
    public function createCampaign(Request $request)
    {
        if (Auth::user()->role !== 'organizer') abort(403);

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required',
            'target_metric' => 'required|numeric',
            'report_id'     => 'required|exists:reports,id',
        ]);

        Campaign::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'target_metric' => $request->target_metric,
            'report_id'     => $request->report_id,
            'organizer_id'  => Auth::id(),
            'status'        => 'open',
        ]);

        return back()->with('success', 'Kampanye berhasil dibuat!');
    }

    private function redirectByRole()
    {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'user') return redirect()->route('user.dashboard');
        return redirect()->route('landing');
    }
}
