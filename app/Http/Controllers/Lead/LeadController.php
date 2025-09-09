<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\LeadRequest;
use App\Http\Resources\Lead\LeadResource;
use App\Models\Lead;
use App\Repositories\Lead\LeadRepository;
use App\Services\Lead\LeadService;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService, protected LeadRepository $leadRepository) {}

    public function index()
    {
        $leadsPaginate = $this->leadRepository->getLeadsPaginate();
        $props = [
            'mailProviders' => $this->leadService->mapProvider(),
            'scanAlgorithm' => $this->leadService->mapScanAlgorithm(),
            'status' => $this->leadService->getStatus(),
            'source' => $this->leadService->getSource(),
            'countries' => $this->leadService->geCountries(),
            'leads' => LeadResource::collection($leadsPaginate),
        ];

        return Inertia::render('Lead/Index', $props);
    }

    public function saveLead(LeadRequest $request)
    {
        $this->leadService->leadUpdateOrCreate($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function socialSync()
    {
        $props = [
            'mailProviders' => $this->leadService->mapProvider(),
            'scanAlgorithm' => $this->leadService->mapScanAlgorithm(),
        ];

        return Inertia::render('Lead/SocialSync', $props);
    }

    public function destroyLead(Lead $lead)
    {
        $lead->delete();

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }
}
