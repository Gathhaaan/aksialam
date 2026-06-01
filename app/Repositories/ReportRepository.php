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

    public function getTotalValidatedReports()
    {
        return Report::whereIn('status', ['verified', 'resolved'])->count();
    }

    public function searchReports($query = null, $category = null)
    {
        $results = Report::query();
        if ($query) $results->where('title', 'like', "%$query%");
        if ($category) $results->where('category', $category);
        return $results->with('user')->latest()->get();
    }

    public function getReportsByStatus($status, $limit = null)
    {
        $query = Report::with('user')->where('status', $status)->latest();
        return $limit ? $query->take($limit)->get() : $query->get();
    }

    public function getRecentReports($limit = 10)
    {
        return Report::with('user')->latest()->take($limit)->get();
    }

    public function getReportsByUser($userId)
    {
        return Report::where('user_id', $userId)->latest()->get();
    }

    public function getReportCounts()
    {
        return [
            'total'    => Report::count(),
            'pending'  => Report::where('status', 'pending')->count(),
            'verified' => Report::where('status', 'verified')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];
    }

    public function deleteReport($id)
    {
        return Report::findOrFail($id)->delete();
    }
}