<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function isCustom(): bool
    {
        return $this->type === 'custom';
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function markAsVerified(): void
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update([
            'verification_status' => 'failed',
        ]);
    }
}
