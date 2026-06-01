<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

/**
 * Policy untuk mengatur otorisasi akses terhadap resource Report.
 * Digunakan oleh controller via $this->authorize() atau Gate::allows().
 */
class ReportPolicy
{
    /**
     * Hanya pemilik laporan yang bisa mengupdate laporannya sendiri.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id;
    }

    /**
     * Pemilik laporan atau admin yang bisa menghapus laporan.
     */
    public function delete(User $user, Report $report): bool
    {
        return $user->id === $report->user_id || $user->role === 'admin';
    }

    /**
     * Hanya organizer dan admin yang bisa memverifikasi laporan.
     */
    public function verify(User $user, Report $report): bool
    {
        return in_array($user->role, ['organizer', 'admin']);
    }

    /**
     * Hanya admin yang bisa mengubah status laporan secara bebas.
     */
    public function changeStatus(User $user, Report $report): bool
    {
        return $user->role === 'admin';
    }
}
