<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function show($id)
    {
        // Mengambil data aksi beserta relasi laporan dan komunitas penyelenggaranya
        $campaign = Campaign::with(['report', 'organizer'])->findOrFail($id);
        
        return view('campaigns.show', compact('campaign'));
    }

    public function join(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $user = auth()->user();

        // Proteksi agar user tidak mendaftar ganda di aksi yang sama
        if (!$campaign->users()->where('user_id', $user->id)->exists()) {
            
            // Masukkan data ke tabel pivot (campaign_user)
            $campaign->users()->attach($user->id, [
                'status' => 'registered',
                'attendance' => 'absent',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Improvisasi Gamifikasi: Berikan bonus 100 XP setelah mendaftar aksi!
            $user->increment('exp_points', 100);
        }

        return redirect()->route('home')->with('success', 'Berhasil mendaftar aksi! Poin kamu bertambah +100 XP.');
    }
}