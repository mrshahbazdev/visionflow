<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GoalCategory;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StrategicGoal extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids, LogsActivity, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'category',
        'time_horizon',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'category' => GoalCategory::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function values(): BelongsToMany
    {
        return $this->belongsToMany(Value::class, 'goal_value');
    }

    public function principles(): BelongsToMany
    {
        return $this->belongsToMany(Principle::class, 'goal_principle');
    }
}
