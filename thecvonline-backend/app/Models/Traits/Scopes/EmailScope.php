<?php

namespace App\Models\Traits\Scopes;

trait EmailScope
{
    public function scopeFilters($query, array $data = [])
    {
        $query->when(!empty($data['city']), function ($q) use ($data) {
            $q->where('city', $data['city']);
        });

        $query->when(!empty($data['requirements']), function ($q) use ($data) {
            $q->where('requirements', 'LIKE', '%'. $data['requirements'] . '%');
        });

        return $query;
    }

    public function scopeListingInfo($query)
    {
        return $query;
    }

    public function scopeSingleInfo($query)
    {
        return $query;
    }
}