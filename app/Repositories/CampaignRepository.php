<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\User;
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

    public function updateCampaign($id, array $data)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update($data);
        return $campaign;
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
        
        // Prevent duplicate entry using syncWithoutDetaching
        $changes = $campaign->volunteers()->syncWithoutDetaching([$userId]);
        
        // syncWithoutDetaching returns an array with an 'attached' key containing IDs that were newly attached.
        // If it's empty, it means the user was already attached.
        return !empty($changes['attached']);
    }

    public function markAttendance($campaignId, $userId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        // Pastikan relawan terdaftar dulu
        if ($campaign->volunteers()->where('user_id', $userId)->exists()) {
            $campaign->volunteers()->updateExistingPivot($userId, ['attendance' => 'attended']);
            return true;
        }
        return false;
    }

    public function getTotalVolunteers()
    {
        return \DB::table('campaign_user')->where('attendance', 'attended')->count();
    }

    public function getTotalImpactMetric()
    {
        return Campaign::where('status', 'finished')->sum('target_metric');
    }

    public function getTopVolunteers($limit = 5)
    {
        return User::where('role', 'user')->orderBy('exp_points', 'desc')->limit($limit)->get();
    }

    public function getCampaignsByOrganizer($organizerId)
    {
        return Campaign::with(['volunteers', 'report'])
            ->where('organizer_id', $organizerId)
            ->latest()->get();
    }

    public function getOpenCampaigns()
    {
        return Campaign::with('organizer')->where('status', 'open')->latest()->get();
    }

    public function deleteCampaign($id)
    {
        return Campaign::findOrFail($id)->delete();
    }
}