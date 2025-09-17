<?php

namespace App\Repositories\Lead;

use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadRepository
{
    public function getLeadsPaginate(array $data): LengthAwarePaginator
    {
        $sortDirection = $data['sort'] ?? 'desc';
        $perPage = $data['perPage'] ?? 10;
        $status = $data['status'] ?? null;
        $search = $data['search'] ?? null;

        $query = Lead::with(['status', 'source', 'country']);

        if ($status) {
            $query->where('status_id', $status);
        }

        if ($search) {
            $query = $this->queryLead($query, $search);
        }

        return $query->orderBy('created_at', $sortDirection)
            ->paginate($perPage);
    }

    public function queryLead($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('company', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%')
                ->orWhere('address', 'like', '%'.$search.'%')
                ->orWhere('city', 'like', '%'.$search.'%')
                ->orWhere('position', 'like', '%'.$search.'%')
                ->orWhereHas('source', function ($sourceQuery) use ($search) {
                    $sourceQuery->where('source', 'like', '%'.$search.'%');
                })
                ->orWhereHas('status', function ($statusQuery) use ($search) {
                    $statusQuery->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('country', function ($countryQuery) use ($search) {
                    $countryQuery->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('tags', function ($tagQuery) use ($search) {
                    $tagQuery->where('name', 'like', '%'.$search.'%');
                });
        });
    }
}
