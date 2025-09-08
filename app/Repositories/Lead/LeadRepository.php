<?php

namespace App\Repositories\Lead;

use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadRepository
{
    public function getLeadsPaginate(): LengthAwarePaginator
    {
        return Lead::with(['status', 'source', 'country'])->orderByDesc('id')->paginate(10);
    }
}
