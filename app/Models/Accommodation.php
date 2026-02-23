<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Accommodation extends Model
{
    protected $fillable = [
        'destination_id', 'name', 'type', 'address', 'check_in', 'check_out',
        'confirmation_code', 'price_per_night', 'currency', 'url', 'notes',
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
            'hotel'   => ['label' => 'Hotel', 'icon' => '🏨'],
            'airbnb'  => ['label' => 'Airbnb', 'icon' => '🏠'],
            'hostel'  => ['label' => 'Hostel', 'icon' => '🛏️'],
            'resort'  => ['label' => 'Resort', 'icon' => '🏖️'],
            'camping' => ['label' => 'Camping', 'icon' => '⛺'],
            'other'   => ['label' => 'Other', 'icon' => '🏡'],
        ];
    }
}
