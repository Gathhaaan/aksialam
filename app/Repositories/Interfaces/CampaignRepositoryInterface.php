<?php

namespace App\Repositories\Interfaces;

interface CampaignRepositoryInterface
{
    public function getAllCampaigns();
    public function getCampaignById($id);
    public function createCampaign(array $data);
    public function updateCampaign($id, array $data);
    public function updateCampaignStatus($id, $status);
    public function joinCampaign($campaignId, $userId);
    public function markAttendance($campaignId, $userId);
    public function getTotalVolunteers();
    public function getTotalImpactMetric();
    public function getTopVolunteers($limit = 5);
    public function getCampaignsByOrganizer($organizerId);
    public function getOpenCampaigns();
    public function deleteCampaign($id);
}