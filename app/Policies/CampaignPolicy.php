<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

/**
 * Policy untuk mengatur otorisasi akses terhadap resource Campaign.
 * Digunakan oleh controller via $this->authorize() atau Gate::allows().
 */
class CampaignPolicy
{
    /**
     * Hanya organizer yang bisa membuat campaign baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'organizer';
    }

    /**
     * Hanya organizer pemilik campaign yang bisa mengupdate.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->organizer_id;
    }

    /**
     * Organizer pemilik atau admin yang bisa menghapus campaign.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->organizer_id || $user->role === 'admin';
    }

    /**
     * User role 'user' yang bisa join campaign (organizer dan admin tidak ikut sebagai relawan).
     */
    public function join(User $user, Campaign $campaign): bool
    {
        return $user->role === 'user' && $campaign->status === 'open';
    }

    /**
     * Hanya organizer pemilik atau admin yang bisa mengelola campaign.
     */
    public function manage(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->organizer_id || $user->role === 'admin';
    }
}
