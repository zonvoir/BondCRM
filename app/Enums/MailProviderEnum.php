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

    public function label(): string
    {
        return match ($this) {
            self::GMAIL => 'Gmail',
            self::OUTLOOK => 'Outlook',
            self::WEBMAIL => 'Webmail',
            self::APPLE_MAIL => 'Apple Mail',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::GMAIL => 'text-red-500',
            self::OUTLOOK => 'text-blue-500',
            self::WEBMAIL => 'text-green-500',
            self::APPLE_MAIL => 'text-gray-500',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::GMAIL => 'bg-red-100',
            self::OUTLOOK => 'bg-blue-100',
            self::WEBMAIL => 'bg-green-100',
            self::APPLE_MAIL => 'bg-gray-100',
        };
    }
}
