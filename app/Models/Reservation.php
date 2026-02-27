<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reservation extends Model
{
    protected $fillable = [
        'destination_id', 'title', 'type', 'datetime', 'venue',
        'address', 'confirmation_code', 'price', 'currency', 'notes',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at');
    }

    public static function types(): array
    {
        return [
            'restaurant' => ['label' => __('destinations.res_type.restaurant'), 'icon' => '🍽️'],
            'transport'  => ['label' => __('destinations.res_type.transport'), 'icon' => '🚂'],
            'flight'     => ['label' => __('destinations.res_type.flight'), 'icon' => '✈️'],
            'activity'   => ['label' => __('destinations.res_type.activity'), 'icon' => '🎭'],
            'show'       => ['label' => __('destinations.res_type.show'), 'icon' => '🎪'],
            'other'      => ['label' => __('destinations.res_type.other'), 'icon' => '📋'],
        ];
    }
}
