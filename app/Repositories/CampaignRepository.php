<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Repositories\Interfaces\CampaignRepositoryInterface;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function getAllCampaigns()
    {
        return Campaign::with(['organizer', 'report', 'volunteers'])->latest()->get();
    }

    public function getCampaignById($id)
    {
        return Campaign::with(['organizer', 'report', 'volunteers'])->findOrFail($id);
    }

    public function createCampaign(array $data)
    {
        return Campaign::create($data);
    }

    public function updateCampaignStatus($id, $status)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update(['status' => $status]);
        return $campaign;
    }

    public function joinCampaign($campaignId, $userId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        // Mencegah duplikasi pendaftaran relawan
        if (!$campaign->volunteers()->where('user_id', $userId)->exists()) {
            $campaign->volunteers()->attach($userId, ['attendance' => 'registered']);
            return true;
        }
        return false;
    }

    public function getTotalVolunteers() {
    // Menghitung relawan yang statusnya 'attended' (hadir)
    return \DB::table('campaign_user')->where('attendance', 'attended')->count();
    }

    public function getTotalImpactMetric() {
        return \App\Models\Campaign::where('status', 'finished')->sum('target_metric');
    }

    public function getTopVolunteers($limit = 5) {
        return \App\Models\User::where('role', 'user')->orderBy('exp_points', 'desc')->limit($limit)->get();
    }
}