<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\BlackListEmailRequest;
use App\Http\Resources\Email\BlackListEmailResource;
use App\Models\Email\BlackListEmail;
use App\Repositories\BlackListEmailRepository;
use App\Services\Email\BlackListEmailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlackListEmailController extends Controller
{
    public function __construct(protected BlackListEmailService $blackListEmailService, protected BlackListEmailRepository $blackListEmailRepository) {}

    public function index(Request $request)
    {
        $blackListEmail = $this->blackListEmailRepository->getBlackListEmailPaginate();

        $props = [
            'blackListEmail' => BlackListEmailResource::collection($blackListEmail),
        ];

        return Inertia::render('Email/BlackListEmail', $props);
    }

    public function saveBlackListEmail(BlackListEmailRequest $request)
    {
        $this->blackListEmailService->saveBlackListEmail($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackListEmail(BlackListEmail $blackList)
    {
        $this->blackListEmailRepository->deleteBlackListEmail($blackList);

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }
}
