<?php

namespace App\Enums;

enum ScanAlgorithmEnum: string
{
    case CHATGPT = 'chatgpt';
    case CUSTOM = 'custom';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::CHATGPT => 'ChatGPT',
            self::CUSTOM => 'Custom',
        };
    }
}
