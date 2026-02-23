<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PointOfInterest extends Model
{
    protected $table = 'points_of_interest';

    protected $fillable = [
        'destination_id', 'name', 'category', 'description', 'address', 'url', 'visited', 'order',
    ];

    protected $casts = [
        'visited' => 'boolean',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at');
    }

    public static function categories(): array
    {
        return [
            'attraction' => ['label' => 'Attraction', 'icon' => '🏛️'],
            'museum'     => ['label' => 'Museum', 'icon' => '🖼️'],
            'restaurant' => ['label' => 'Restaurant', 'icon' => '🍽️'],
            'cafe'       => ['label' => 'Café', 'icon' => '☕'],
            'bar'        => ['label' => 'Bar', 'icon' => '🍺'],
            'park'       => ['label' => 'Park / Nature', 'icon' => '🌿'],
            'shopping'   => ['label' => 'Shopping', 'icon' => '🛍️'],
            'viewpoint'  => ['label' => 'Viewpoint', 'icon' => '🌅'],
            'nightlife'  => ['label' => 'Nightlife', 'icon' => '🎉'],
            'other'      => ['label' => 'Other', 'icon' => '📍'],
        ];
    }
}
