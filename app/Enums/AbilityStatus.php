<?php

namespace App\Enums;

enum AbilityStatus: string
{
    case Allow = 'allow';
    case Inherit = 'inherit';
    case Deny = 'deny';
}
