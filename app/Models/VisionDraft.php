<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisionDraft extends Model
{
    use HasUlids;

    protected $fillable = [
        'vision_id',
        'author_id',
        'content',
    ];

    public function vision(): BelongsTo
    {
        return $this->belongsTo(Vision::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(VisionComment::class, 'vision_draft_id');
    }
}
