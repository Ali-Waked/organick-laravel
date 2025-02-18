<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Accepted = 'accepted';
    case Completed = 'completed';
    case Unaccepted = 'unaccepted';
    case Canceled = 'canceled';
}
