<?php

declare(strict_types=1);

namespace App\Enums;

enum VisionStatus: string
{
    case Drafting = 'drafting';
    case Reviewing = 'reviewing';
    case Approved = 'approved';
}
