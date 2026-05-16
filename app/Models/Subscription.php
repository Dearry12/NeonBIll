<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;

    public const CURRENCIES = ['IDR', 'USD', 'EUR', 'GBP', 'SGD', 'JPY'];

    public const BILLING_CYCLES = ['Monthly', 'Yearly'];

    public const CATEGORIES = [
        'Streaming',
        'Internet',
        'Software',
        'Gaming',
        'Utilities',
        'Other',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'price',
        'currency',
        'billing_cycle',
        'next_due_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'next_due_date' => 'date',
            'is_active' => 'boolean',
            'price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }
}
