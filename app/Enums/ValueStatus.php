<?php

declare(strict_types=1);

namespace App\Enums;

enum ValueStatus: string
{
    case Draft = 'draft';
    case Proposed = 'proposed';
    case Approved = 'approved';
    case Archived = 'archived';
}
