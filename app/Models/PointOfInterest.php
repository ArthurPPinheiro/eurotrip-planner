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
            'attraction' => ['label' => __('destinations.poi_cat.attraction'), 'icon' => '🏛️'],
            'museum'     => ['label' => __('destinations.poi_cat.museum'), 'icon' => '🖼️'],
            'restaurant' => ['label' => __('destinations.poi_cat.restaurant'), 'icon' => '🍽️'],
            'cafe'       => ['label' => __('destinations.poi_cat.cafe'), 'icon' => '☕'],
            'bar'        => ['label' => __('destinations.poi_cat.bar'), 'icon' => '🍺'],
            'park'       => ['label' => __('destinations.poi_cat.park'), 'icon' => '🌿'],
            'shopping'   => ['label' => __('destinations.poi_cat.shopping'), 'icon' => '🛍️'],
            'viewpoint'  => ['label' => __('destinations.poi_cat.viewpoint'), 'icon' => '🌅'],
            'nightlife'  => ['label' => __('destinations.poi_cat.nightlife'), 'icon' => '🎉'],
            'other'      => ['label' => __('destinations.poi_cat.other'), 'icon' => '📍'],
        ];
    }
}
