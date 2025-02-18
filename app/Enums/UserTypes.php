<?php

namespace App\Enums;

enum UserTypes: string
{
    case Customer = 'customer';
    case Admin = 'admin';
    case Moderator = 'moderator';
    case Driver = 'driver';

    public static function getAllTypes(...$except): array
    {
        return array_values(array_filter(
            self::cases(),
            fn($type) => !in_array($type->value, $except)
        ));
    }
}
