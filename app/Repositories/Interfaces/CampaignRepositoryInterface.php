<?php

namespace App\Repositories\Interfaces;

interface CampaignRepositoryInterface
{
    public function getAllCampaigns();
    public function getCampaignById($id);
    public function createCampaign(array $data);
    public function updateCampaignStatus($id, $status);
    public function joinCampaign($campaignId, $userId);
}