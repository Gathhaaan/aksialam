<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Campaign;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Pastikan hanya role 'admin' yang bisa akses
        if (Auth::user()->role !== 'admin') {
            return $this->redirectByRole();
        }

        // Statistik global sistem
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_organizers'  => User::where('role', 'organizer')->count(),
            'total_reports'     => Report::count(),
            'pending_reports'   => Report::where('status', 'pending')->count(),
            'verified_reports'  => Report::where('status', 'verified')->count(),
            'total_campaigns'   => Campaign::count(),
            'open_campaigns'    => Campaign::where('status', 'open')->count(),
            'total_volunteers'  => \DB::table('campaign_user')->count(),
        ];

        // Semua laporan terbaru
        $recentReports = Report::with('user')->latest()->take(10)->get();

        // Semua user terbaru
        $recentUsers = User::latest()->take(8)->get();

        // Semua campaign
        $allCampaigns = Campaign::with(['organizer', 'volunteers'])->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentReports', 'recentUsers', 'allCampaigns'
        ));
    }

    // Ubah status laporan (admin bisa approve/reject)
    public function updateReportStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate(['status' => 'required|in:pending,verified,resolved,rejected']);
        Report::findOrFail($id)->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan berhasil diubah!');
    }

    // Hapus user
    public function deleteUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        if ($id == Auth::id()) return back()->with('error', 'Tidak dapat menghapus akun sendiri!');

        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // Ubah role user
    public function changeUserRole(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate(['role' => 'required|in:user,organizer,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diubah!');
    }

    private function redirectByRole()
    {
        $role = Auth::user()->role;
        if ($role === 'organizer') return redirect()->route('organizer.dashboard');
        if ($role === 'user') return redirect()->route('user.dashboard');
        return redirect()->route('landing');
    }
}
