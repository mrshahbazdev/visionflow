<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TeamRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'preferred_locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function organizationMembership(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function workAssignments(): HasMany
    {
        return $this->hasMany(WorkAssignment::class, 'assigned_to');
    }

    public function teamRole(): ?TeamRole
    {
        $membership = OrganizationMember::where('user_id', $this->id)
            ->where('organization_id', $this->organization_id)
            ->first();

        return $membership?->role;
    }

    public function hasTeamRole(TeamRole ...$roles): bool
    {
        $currentRole = $this->teamRole();

        if ($currentRole === null) {
            return false;
        }

        return in_array($currentRole, $roles, true);
    }
}
