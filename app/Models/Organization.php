<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_url',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }

    public function principles(): HasMany
    {
        return $this->hasMany(Principle::class);
    }

    public function strategicGoals(): HasMany
    {
        return $this->hasMany(StrategicGoal::class);
    }

    public function visions(): HasMany
    {
        return $this->hasMany(Vision::class);
    }

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function decisionLogs(): HasMany
    {
        return $this->hasMany(DecisionLog::class);
    }
}
