<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ValueStatus;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Value extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'status',
        'sort_order',
        'version',
        'approved_at',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ValueStatus::class,
            'approved_at' => 'datetime',
            'sort_order' => 'integer',
            'version' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(ValueCard::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ValueVote::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ValueVersion::class);
    }

    public function principles(): HasMany
    {
        return $this->hasMany(Principle::class);
    }

    public function strategicGoals(): BelongsToMany
    {
        return $this->belongsToMany(StrategicGoal::class, 'goal_value');
    }
}
