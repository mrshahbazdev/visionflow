<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ConsensusVote;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrincipleConsensus extends Model
{
    use HasUlids;

    protected $table = 'principle_consensus';

    protected $fillable = [
        'principle_id',
        'user_id',
        'vote',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'vote' => ConsensusVote::class,
        ];
    }

    public function principle(): BelongsTo
    {
        return $this->belongsTo(Principle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
