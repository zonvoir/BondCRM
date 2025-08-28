<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use App\Services\Lead\LeadService;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService) {}

    public function index()
    {
        $props = [
            'mailProviders' => $this->leadService->mapProvider(),
            'scanAlgorithm' => $this->leadService->mapScanAlgorithm(),
        ];

        return Inertia::render('Lead/Index', $props);
    }
}
