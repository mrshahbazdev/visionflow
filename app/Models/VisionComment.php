<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisionComment extends Model
{
    use HasUlids;

    protected $fillable = [
        'vision_draft_id',
        'user_id',
        'body',
        'parent_id',
        'emotional_resonance',
    ];

    protected function casts(): array
    {
        return [
            'emotional_resonance' => 'integer',
        ];
    }

    public function draft(): BelongsTo
    {
        return $this->belongsTo(VisionDraft::class, 'vision_draft_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(VisionComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(VisionComment::class, 'parent_id');
    }
}
