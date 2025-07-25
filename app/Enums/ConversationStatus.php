<?php

namespace App\Enums;

enum ConversationStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
