<?php

namespace App\Services\Mails;

use App\Models\User;

class GmailService
{
    private const GMAIL_API_BASE = 'https://gmail.googleapis.com/gmail/v1/users/me';

    private const OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';

    public function __construct(public User $user) {}

}
