<?php

declare(strict_types=1);

namespace App\Enums;

enum PrincipleStatus: string
{
    case Draft = 'draft';
    case Proposed = 'proposed';
    case Approved = 'approved';
}
