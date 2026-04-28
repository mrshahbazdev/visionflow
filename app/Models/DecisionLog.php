<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionLog extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids;

    protected $fillable = [
        'organization_id',
        'user_id',
        'title',
        'description',
        'decision',
        'supporting_value_id',
        'supporting_mission_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supportingValue(): BelongsTo
    {
        return $this->belongsTo(Value::class, 'supporting_value_id');
    }

    public function supportingMission(): BelongsTo
    {
        return $this->belongsTo(Mission::class, 'supporting_mission_id');
    }
}
