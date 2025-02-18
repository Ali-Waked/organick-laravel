<?php

namespace App\Enums;

enum CategoryStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
    public function getStatus(): array
    {
        foreach (CategoryStatus::cases() as $status) {
            $allStatusValue[] = $status->value;
        }
        return $allStatusValue;
    }
}
