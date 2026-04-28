<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VisionStatus;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Vision extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'content',
        'status',
        'version',
        'approved_at',
        'approved_by',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'status' => VisionStatus::class,
            'approved_at' => 'datetime',
            'is_current' => 'boolean',
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

    public function drafts(): HasMany
    {
        return $this->hasMany(VisionDraft::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(VisionApproval::class);
    }

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }
}
