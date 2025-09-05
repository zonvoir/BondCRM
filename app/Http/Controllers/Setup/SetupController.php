<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\OpenAiRequest;
use App\Http\Requests\Setup\SourcesRequest;
use App\Http\Requests\Setup\StatusRequest;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use App\Repositories\Setup\SetupRepository;
use App\Services\Setup\SettingsService;
use App\Services\Setup\SetupService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function __construct(protected SettingsService $settingsService, protected SetupRepository $setupRepository, protected SetupService $setupService) {}

    public function generalSettings(Request $request, $type)
    {
        $props = [
            'menuSettings' => $this->setupService->adminMenuSettings(),
            'data' => $this->setupService->propsAdminGeneral($type),
        ];

        return Inertia::render('Setup/Settings/'.ucfirst($type), $props);
    }

    public function openAiSaveSettings(OpenAiRequest $request)
    {
        $this->setupService->saveOpenAiSettings($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function source()
    {
        $props = [
            'sources' => $this->setupRepository->getSource(),
        ];

        return Inertia::render('Setup/Source', $props);
    }

    public function sourcesSave(SourcesRequest $request)
    {
        $this->setupService->saveSource($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function sourcesDestroy(Sources $source)
    {
        $source->delete();

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function statuses()
    {
        $props = [
            'statuses' => $this->setupRepository->getStatus(),
        ];

        return Inertia::render('Setup/Status', $props);
    }

    public function statusSave(StatusRequest $request)
    {
        $this->setupService->saveStatus($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function statusDestroy(Status $status)
    {
        $status->delete();

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }
}
