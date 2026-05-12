<?php

namespace App\Repositories;

use App\Models\Report;
use App\Repositories\Interfaces\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    public function getAllReports()
    {
        return Report::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function getReportById($id)
    {
        return Report::with('user')->findOrFail($id);
    }

    public function createReport(array $data)
    {
        return Report::create($data);
    }

    public function updateReportStatus($id, $status)
    {
        $report = Report::findOrFail($id);
        $report->update(['status' => $status]);
        return $report;
    }

    public function getTotalValidatedReports() {
    return \App\Models\Report::whereIn('status', ['verified', 'resolved'])->count();
    }

    // Fungsi pencarian baru
    public function searchReports($query = null, $category = null) {
        $results = \App\Models\Report::query();
        if ($query) $results->where('title', 'like', "%$query%");
        if ($category) $results->where('category', $category);
        return $results->with('user')->latest()->get();
    }
}