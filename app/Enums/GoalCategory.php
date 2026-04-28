<?php

declare(strict_types=1);

namespace App\Enums;

enum GoalCategory: string
{
    case Market = 'market';
    case Impact = 'impact';
    case Organization = 'organization';
}
