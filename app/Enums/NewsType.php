<?php

namespace App\Enums;

enum NewsType: string
{
    case PRODUCT = 'product';
    case EVENT = 'event';
    case ANNOUNCEMENT = 'announcement';
    case ALERT = 'alert';
    case PROMOTION = 'promotion';
    case TIP = 'tip';
    case BLOG = 'news';
    // case PRESS_RELEASE = 'press_release';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
