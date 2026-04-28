<?php

declare(strict_types=1);

namespace App\Enums;

enum ConsensusVote: string
{
    case Agree = 'agree';
    case Disagree = 'disagree';
    case Abstain = 'abstain';
}
