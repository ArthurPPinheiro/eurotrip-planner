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
            'restaurant' => ['label' => 'Restaurant', 'icon' => '🍽️'],
            'transport'  => ['label' => 'Transport', 'icon' => '🚂'],
            'flight'     => ['label' => 'Flight', 'icon' => '✈️'],
            'activity'   => ['label' => 'Activity / Tour', 'icon' => '🎭'],
            'show'       => ['label' => 'Show / Event', 'icon' => '🎪'],
            'other'      => ['label' => 'Other', 'icon' => '📋'],
        ];
    }
}
