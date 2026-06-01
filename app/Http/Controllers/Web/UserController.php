<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Campaign;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Laporan milik user ini
        $user = auth()->user();
        $myReports = $user->reports()->latest()->get();
        $myCampaigns = $user->joinedCampaigns()->latest()->get();
        
        // Ambil riwayat penukaran reward
        $myRewards = $user->rewards()->latest('user_rewards.created_at')->get();

        // Hitung peringkat (berdasarkan exp_points terbesar)
        $userRank = User::where('role', 'user')->where('exp_points', '>', $user->exp_points)->count() + 1;
        
        $leaderboard = User::where('role', 'user')
                           ->orderBy('exp_points', 'desc')
                           ->take(5)
                           ->get();

        return view('user.dashboard', compact('user', 'myReports', 'myCampaigns', 'myRewards', 'userRank', 'leaderboard'));
    }
}
