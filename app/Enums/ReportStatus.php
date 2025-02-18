<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Archived = 'archived';
    case Published = 'published';
}
