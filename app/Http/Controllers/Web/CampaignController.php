<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

class CampaignController extends Controller
{
    protected $campaignRepository;

    public function __construct(CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function show($id)
    {
        // Mengambil data aksi beserta relasi (via repository)
        $campaign = $this->campaignRepository->getCampaignById($id);

        return view('campaigns.show', compact('campaign'));
    }

    public function join(Request $request, $id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);
        $user = auth()->user();

        // Validasi bisnis: campaign harus berstatus 'open'
        if ($campaign->status !== 'open') {
            return back()->with('error', 'Kampanye ini sudah tidak menerima pendaftaran.');
        }

        // Validasi bisnis: cek kuota relawan belum penuh
        if ($campaign->volunteers()->count() >= $campaign->max_volunteers) {
            return back()->with('error', 'Kuota relawan untuk kampanye ini sudah penuh.');
        }

        // Daftarkan relawan via repository (akan cek duplikasi di repository)
        $joined = $this->campaignRepository->joinCampaign($id, $user->id);

        if ($joined) {
            // Improvisasi Gamifikasi: Berikan bonus 100 XP setelah mendaftar aksi!
            $user->increment('exp_points', 100);
            return redirect()->route('user.dashboard')->with('success', 'Berhasil mendaftar aksi! Poin kamu bertambah +100 XP. 🎉');
        }

        return redirect()->route('user.dashboard')->with('info', 'Kamu sudah terdaftar di aksi ini sebelumnya.');
    }

    public function checkin(Request $request, $id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);
        $user = auth()->user();

        // 1. Cek apakah user terdaftar di campaign ini
        if (!$campaign->volunteers()->where('user_id', $user->id)->exists()) {
            return redirect()->route('campaigns.show', $id)->with('error', 'Kamu belum terdaftar di aksi ini, silakan daftar terlebih dahulu sebelum absen.');
        }

        // 2. Cek apakah sudah absen sebelumnya
        $pivot = $campaign->volunteers()->where('user_id', $user->id)->first()->pivot;
        if ($pivot->attendance === 'attended') {
            return redirect()->route('campaigns.show', $id)->with('info', 'Kamu sudah melakukan absensi (check-in) untuk aksi ini sebelumnya.');
        }

        // 3. Mark attendance
        $marked = $this->campaignRepository->markAttendance($id, $user->id);

        if ($marked) {
            // Berikan +50 XP sebagai reward telah hadir secara fisik
            $user->increment('exp_points', 50);
            return redirect()->route('campaigns.show', $id)->with('success', 'Berhasil Check-In! Kehadiranmu telah tercatat dan kamu mendapatkan +50 XP. Terima kasih pahlawan bumi! 🌍');
        }

        return redirect()->route('campaigns.show', $id)->with('error', 'Terjadi kesalahan sistem saat mencoba absensi.');
    }
}