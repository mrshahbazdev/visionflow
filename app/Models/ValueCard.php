<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ValueCard extends Model
{
    use BelongsToOrganization, HasFactory, HasUlids;

    protected $fillable = [
        'value_id',
        'organization_id',
        'author_id',
        'content',
        'is_anonymous',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function clusters(): BelongsToMany
    {
        return $this->belongsToMany(ValueCluster::class, 'value_card_cluster');
    }
}
