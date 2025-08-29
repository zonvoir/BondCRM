<?php

namespace App\Http\Controllers\Black;

use App\Http\Controllers\Controller;
use App\Http\Requests\Black\BlackEmailRequest;
use App\Http\Requests\Black\BlackListEmailRequest;
use App\Http\Requests\Black\BlackListKeywordRequest;
use App\Http\Resources\Email\BlackListEmailResource;
use App\Models\Black\BlackEmail;
use App\Models\Black\BlackEmailText;
use App\Models\Black\BlackListKeyword;
use App\Repositories\Black\BlackRepository;
use App\Services\Black\BlackService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlackingController extends Controller
{
    public function __construct(protected BlackService $blackService, protected BlackRepository $blackRepository) {}

    public function index(Request $request)
    {
        $blackListEmail = $this->blackRepository->getBlackEmailTextPaginate();

        $props = [
            'blackListEmail' => BlackListEmailResource::collection($blackListEmail),
        ];

        return Inertia::render('Black/BlackListEmail', $props);
    }

    public function saveBlackListEmail(BlackListEmailRequest $request)
    {
        $this->blackService->saveBlackEmailTextUpload($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackListEmail(BlackEmailText $blackList)
    {
        $this->blackRepository->deleteBlackListEmail($blackList);

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function blackEmail()
    {
        $props = [
            'emails' => $this->blackRepository->getBlackEmailPaginate(),
        ];

        return Inertia::render('Black/Email', $props);
    }

    public function blackEmailSave(BlackEmailRequest $request)
    {
        $this->blackService->saveBlackEmail($request->validated());

        return back()->with([
            'message' => 'save successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackEmail(BlackEmail $blackEmail)
    {
        $blackEmail->delete();

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackEmails(Request $request)
    {
        BlackEmail::query()->whereIn('id', $request->ids)->delete();

        return back()->with([
            'message' => 'selected delete successfully!',
            'type' => 'success',
        ]);
    }

    public function blackWord()
    {
        $props = [
            'keywords' => $this->blackRepository->getBlackListKeywordPaginate(),
        ];

        return Inertia::render('Black/BlackWord', $props);
    }

    public function saveBlackWord(BlackListKeywordRequest $request)
    {
        $this->blackService->saveBlackListKeywordEmail($request->validated());

        return back()->with([
            'message' => 'save successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackListKeyword(BlackListKeyword $blackListKeyword)
    {
        $blackListKeyword->delete();

        return back()->with([
            'message' => 'Deleted successfully',
            'type' => 'success',
        ]);
    }

    public function destroyBlackListKeywords(Request $request)
    {
        BlackListKeyword::query()->whereIn('id', $request->ids)->delete();

        return back()->with([
            'message' => 'selected delete successfully!',
            'type' => 'success',
        ]);
    }
}
