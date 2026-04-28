<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Leader = 'leader';
    case Member = 'member';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::Admin => 'Admin',
            self::Leader => 'Leader',
            self::Member => 'Member',
            self::Viewer => 'Viewer',
        };
    }

    public function canManageMembers(): bool
    {
        return in_array($this, [self::Owner, self::Admin], true);
    }

    public function canAssignWork(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Leader], true);
    }

    public function canEditContent(): bool
    {
        return in_array($this, [self::Owner, self::Admin, self::Leader, self::Member], true);
    }

    public function canViewContent(): bool
    {
        return true;
    }

    public function hierarchy(): int
    {
        return match ($this) {
            self::Owner => 100,
            self::Admin => 80,
            self::Leader => 60,
            self::Member => 40,
            self::Viewer => 20,
        };
    }
}
