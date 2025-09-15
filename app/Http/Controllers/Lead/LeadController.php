<?php

namespace App\Http\Controllers\Lead;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\ImportRequest;
use App\Http\Requests\Lead\LeadBulkActionRequest;
use App\Http\Requests\Lead\LeadRequest;
use App\Http\Resources\Lead\LeadResource;
use App\Imports\LeadsImport;
use App\Models\Lead;
use App\Repositories\Lead\LeadRepository;
use App\Services\Lead\LeadService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService, protected LeadRepository $leadRepository) {}

    public function index(Request $request)
    {
        $leadsPaginate = $this->leadRepository->getLeadsPaginate($request->all());
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

    public function export(Request $request, $type)
    {
        $filename = 'leads_'.now()->format('Y_m_d_H_i_s');

        return match ($type) {
            'csv' => Excel::download(
                new LeadsExport($request),
                $filename.'.csv',
                ExcelFormat::CSV
            ),
            'pdf' => Excel::download(
                new LeadsExport($request),
                $filename.'.pdf',
                ExcelFormat::MPDF
            ),
            default => Excel::download(
                new LeadsExport($request),
                $filename.'.xlsx',
                ExcelFormat::XLSX
            ),
        };
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

    public function import(Request $request)
    {
        $props = [
            'status' => $this->leadService->getStatus(),
            'source' => $this->leadService->getSource(),
            'sampleFile' => asset('assets/lead/leads_example.xlsx'),
        ];

        return Inertia::render('Lead/import', $props);
    }

    public function importSimulate(ImportRequest $request)
    {
        $data = $this->leadService->importSimulate($request->validated());

        return response()->json(['data' => $data], 200);
    }

    public function importSave(ImportRequest $request)
    {
        try {
            Excel::import(new LeadsImport, $request->file);

            return redirect()->route('employee.lead.import')->with('success', 'Data imported successfully!');
        } catch (Exception $e) {
            return redirect()->route('employee.lead.import')->with('error', $e->getMessage());
        }
    }

    public function leadDetails(Lead $lead)
    {
        $props = [

        ];

        return Inertia::render('Lead/Details', $props);
    }

    public function bulkAction(LeadBulkActionRequest $request)
    {
        $action = $this->leadService->bulkAction($request->validated());

        if ($action['deleted'] > 0 && $action['updated'] > 0) {
            $message = "{$action['deleted']} leads deleted and {$action['updated']} leads updated successfully.";
        } elseif ($action['deleted'] > 0) {
            $message = "{$action['deleted']} leads deleted successfully.";
        } elseif ($action['updated'] > 0) {
            $message = "{$action['updated']} leads updated successfully.";
        } else {
            return back();
        }

        return back()->with([
            'message' => $message,
            'type' => 'success',
        ]);
    }
}
