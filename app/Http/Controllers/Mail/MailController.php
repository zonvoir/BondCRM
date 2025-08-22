<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailController extends Controller
{
    public function gmailList(Request $request)
    {

        $props = [

        ];

        return Inertia::render('Mails/Gmail', $props);
    }

    public function outlookList()
    {
        $props = [

        ];

        return Inertia::render('Mails/Outlook', $props);
    }

    public function webMailList()
    {
        $props = [

        ];

        return Inertia::render('Mails/WebMail', $props);
    }

    public function appleMailList()
    {
        $props = [

        ];

        return Inertia::render('Mails/AppleMail', $props);
    }
}
