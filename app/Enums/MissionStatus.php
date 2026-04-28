<?php

declare(strict_types=1);

namespace App\Enums;

enum MissionStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Archived = 'archived';
}
