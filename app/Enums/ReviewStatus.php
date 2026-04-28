<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewStatus: string
{
    case OnTrack = 'on_track';
    case AtRisk = 'at_risk';
    case OffTrack = 'off_track';
}
