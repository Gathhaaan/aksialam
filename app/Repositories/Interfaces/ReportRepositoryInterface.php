<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface
{
    public function getAllReports();
    public function getReportById($id);
    public function createReport(array $data);
    public function updateReportStatus($id, $status);
    public function getTotalValidatedReports();
    public function searchReports($query = null, $category = null);
    public function getReportsByStatus($status, $limit = null);
    public function getRecentReports($limit = 10);
    public function getReportsByUser($userId);
    public function getReportCounts();
    public function deleteReport($id);
}