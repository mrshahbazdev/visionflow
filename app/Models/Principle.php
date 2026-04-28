<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PrincipleStatus;
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

class Principle extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'value_id',
        'statement',
        'template_key',
        'status',
        'alignment_score',
    ];

    protected function casts(): array
    {
        return [
            'status' => PrincipleStatus::class,
            'alignment_score' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class);
    }

    public function consensusVotes(): HasMany
    {
        return $this->hasMany(PrincipleConsensus::class);
    }

    public function strategicGoals(): BelongsToMany
    {
        return $this->belongsToMany(StrategicGoal::class, 'goal_principle');
    }
}
