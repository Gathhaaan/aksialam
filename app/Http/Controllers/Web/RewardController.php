<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = \App\Models\Reward::all();
        $user = auth()->user();
        
        return view('user.rewards', compact('rewards', 'user'));
    }

    public function redeem(\Illuminate\Http\Request $request, $id)
    {
        $user = auth()->user();
        $reward = \App\Models\Reward::findOrFail($id);

        if ($user->exp_points < $reward->points_required) {
            return redirect()->back()->with('error', 'EXP Points Anda tidak mencukupi untuk menukar reward ini.');
        }

        if ($reward->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok reward ini sudah habis.');
        }

        // Kurangi poin
        $user->exp_points -= $reward->points_required;
        $user->save();

        // Kurangi stok
        $reward->stock -= 1;
        $reward->save();

        // Catat riwayat
        $user->rewards()->attach($reward->id, ['status' => 'pending']);

        // Kirim Email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\RewardClaimed($user, $reward));
        } catch (\Exception $e) {
            // Abaikan error email di prototype jika konfigurasi belum pas, agar tidak memblokir penukaran
            \Illuminate\Support\Facades\Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Berhasil menukar reward! Voucher atau petunjuk klaim akan dikirimkan ke email Anda.');
    }
}
