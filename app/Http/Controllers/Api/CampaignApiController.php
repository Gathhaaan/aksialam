<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

/**
 * API Controller untuk resource Campaign (Kampanye Aksi Alam).
 * Mendukung full CRUD + join campaign dengan autentikasi JWT.
 * Repository Pattern digunakan untuk semua operasi data.
 */
class CampaignApiController extends Controller
{
    private $campaignRepository;

    public function __construct(CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * GET /api/v1/campaigns
     * Menampilkan semua campaign. Mendukung filter berdasarkan status.
     */
    public function index(Request $request)
    {
        if ($request->has('status')) {
            $campaigns = \App\Models\Campaign::with(['organizer', 'report', 'volunteers'])
                ->where('status', $request->query('status'))
                ->latest()->get();
        } else {
            $campaigns = $this->campaignRepository->getAllCampaigns();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar kampanye berhasil diambil',
            'data'    => $campaigns,
            'total'   => $campaigns->count(),
        ], 200);
    }

    /**
     * GET /api/v1/campaigns/{id}
     * Menampilkan detail campaign termasuk relawan yang terdaftar.
     */
    public function show($id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail kampanye',
            'data'    => $campaign,
            'volunteers_count' => $campaign->volunteers->count(),
        ], 200);
    }

    /**
     * POST /api/v1/campaigns
     * Membuat campaign baru. Hanya organizer yang bisa membuat.
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        // Authorization: hanya organizer
        if ($user->role !== 'organizer') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya organizer yang bisa membuat kampanye.',
            ], 403);
        }

        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'event_date'     => 'required|date|after:today',
            'max_volunteers' => 'required|integer|min:1',
            'target_metric'  => 'required|numeric|min:0',
            'metric_unit'    => 'required|string|max:50',
            'report_id'      => 'nullable|exists:reports,id',
        ]);

        $data = $request->only(['title', 'description', 'event_date', 'max_volunteers', 'target_metric', 'metric_unit', 'report_id']);
        $data['organizer_id'] = $user->id;
        $data['status'] = 'open';

        $campaign = $this->campaignRepository->createCampaign($data);

        return response()->json([
            'success' => true,
            'message' => 'Kampanye berhasil dibuat',
            'data'    => $campaign->load(['organizer', 'report']),
        ], 201);
    }

    /**
     * PUT /api/v1/campaigns/{id}
     * Mengupdate campaign. Hanya organizer pemilik yang bisa mengupdate.
     */
    public function update(Request $request, $id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);
        $user = auth('api')->user();

        // Authorization: hanya pemilik campaign
        if ($campaign->organizer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah kampanye ini.',
            ], 403);
        }

        $request->validate([
            'title'          => 'sometimes|string|max:255',
            'description'    => 'sometimes|nullable|string',
            'event_date'     => 'sometimes|date|after:today',
            'max_volunteers' => 'sometimes|integer|min:1',
            'target_metric'  => 'sometimes|numeric|min:0',
            'metric_unit'    => 'sometimes|string|max:50',
            'status'         => 'sometimes|in:open,closed,finished',
        ]);

        $this->campaignRepository->updateCampaign($id, $request->only([
            'title', 'description', 'event_date', 'max_volunteers',
            'target_metric', 'metric_unit', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Kampanye berhasil diperbarui',
            'data'    => $this->campaignRepository->getCampaignById($id),
        ], 200);
    }

    /**
     * DELETE /api/v1/campaigns/{id}
     * Menghapus campaign. Hanya pemilik atau admin yang bisa menghapus.
     */
    public function destroy($id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);
        $user = auth('api')->user();

        if ($campaign->organizer_id !== $user->id && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus kampanye ini.',
            ], 403);
        }

        $this->campaignRepository->deleteCampaign($id);

        return response()->json([
            'success' => true,
            'message' => 'Kampanye berhasil dihapus',
        ], 200);
    }

    /**
     * POST /api/v1/campaigns/{id}/join
     * Mendaftarkan user sebagai relawan di campaign.
     */
    public function join($id)
    {
        $campaign = $this->campaignRepository->getCampaignById($id);
        $user = auth('api')->user();

        // Validasi bisnis
        if ($campaign->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye ini sudah tidak menerima pendaftaran.',
            ], 422);
        }

        if ($campaign->volunteers()->count() >= $campaign->max_volunteers) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota relawan untuk kampanye ini sudah penuh.',
            ], 422);
        }

        $joined = $this->campaignRepository->joinCampaign($id, $user->id);

        if ($joined) {
            $user->increment('exp_points', 100);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendaftar sebagai relawan! +100 XP',
                'exp_points' => $user->fresh()->exp_points,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Anda sudah terdaftar di kampanye ini.',
        ], 409);
    }
}
