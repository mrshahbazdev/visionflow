<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValueVote extends Model
{
    use HasUlids;

    protected $fillable = [
        'value_id',
        'user_id',
        'score',
        'round',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'round' => 'integer',
        ];
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
