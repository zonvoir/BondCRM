<?php

namespace App\Enums;

enum MailProviderEnum: string
{
    case GMAIL = 'gmail';
    case OUTLOOK = 'outlook';
    case WEBMAIL = 'webmail';
    case APPLE_MAIL = 'apple-mail';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
