<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MissionStatus;
use App\Enums\ReviewCadence;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Mission extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'vision_id',
        'title',
        'description',
        'template_key',
        'owner_id',
        'status',
        'review_cadence',
        'next_review_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => MissionStatus::class,
            'review_cadence' => ReviewCadence::class,
            'next_review_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function vision(): BelongsTo
    {
        return $this->belongsTo(Vision::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(MissionReview::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
