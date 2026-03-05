<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\DayRoute;
use App\Models\RouteStop;
use Illuminate\Http\Request;

class DayRouteController extends Controller
{
    public function store(Request $request, Day $day)
    {
        $data = $request->validate([
            'transport_mode'        => 'required|in:car,bus,train',
            'total_distance_km'     => 'nullable|numeric|min:0',
            'total_duration_minutes'=> 'nullable|integer|min:0',
            'stops'                 => 'required|array|min:2',
            'stops.*.city'          => 'required|string|max:255',
            'stops.*.country'       => 'nullable|string|max:255',
            'stops.*.latitude'      => 'required|numeric|between:-90,90',
            'stops.*.longitude'     => 'required|numeric|between:-180,180',
        ]);

        $route = DayRoute::create([
            'day_id'                 => $day->id,
            'transport_mode'         => $data['transport_mode'],
            'total_distance_km'      => $data['total_distance_km'] ?? null,
            'total_duration_minutes' => $data['total_duration_minutes'] ?? null,
        ]);

        foreach ($data['stops'] as $index => $stop) {
            RouteStop::create([
                'day_route_id' => $route->id,
                'city'         => $stop['city'],
                'country'      => $stop['country'] ?? null,
                'latitude'     => $stop['latitude'],
                'longitude'    => $stop['longitude'],
                'order'        => $index,
            ]);
        }

        if ($request->wantsJson()) {
            $route->load('stops');
            $day->setRelation('route', $route);
            $stops = $route->stops->map(fn($s) => [
                'city'      => $s->city,
                'latitude'  => (float) $s->latitude,
                'longitude' => (float) $s->longitude,
            ])->values()->toArray();
            return response()->json([
                'html'       => view('trips._route_section', ['day' => $day])->render(),
                'update_url' => route('routes.update', $route),
                'stops'      => $stops,
                'mode'       => $route->transport_mode,
                'message'    => __('messages.route.added'),
            ]);
        }

        return back()->with('success', __('messages.route.added'));
    }

    public function update(Request $request, DayRoute $route)
    {
        $data = $request->validate([
            'transport_mode'        => 'required|in:car,bus,train',
            'total_distance_km'     => 'nullable|numeric|min:0',
            'total_duration_minutes'=> 'nullable|integer|min:0',
            'stops'                 => 'required|array|min:2',
            'stops.*.city'          => 'required|string|max:255',
            'stops.*.country'       => 'nullable|string|max:255',
            'stops.*.latitude'      => 'required|numeric|between:-90,90',
            'stops.*.longitude'     => 'required|numeric|between:-180,180',
        ]);

        $route->update([
            'transport_mode'         => $data['transport_mode'],
            'total_distance_km'      => $data['total_distance_km'] ?? null,
            'total_duration_minutes' => $data['total_duration_minutes'] ?? null,
        ]);

        $route->stops()->delete();

        foreach ($data['stops'] as $index => $stop) {
            RouteStop::create([
                'day_route_id' => $route->id,
                'city'         => $stop['city'],
                'country'      => $stop['country'] ?? null,
                'latitude'     => $stop['latitude'],
                'longitude'    => $stop['longitude'],
                'order'        => $index,
            ]);
        }

        if ($request->wantsJson()) {
            $route->load('stops');
            $day = $route->day;
            $day->setRelation('route', $route);
            $stops = $route->stops->map(fn($s) => [
                'city'      => $s->city,
                'latitude'  => (float) $s->latitude,
                'longitude' => (float) $s->longitude,
            ])->values()->toArray();
            return response()->json([
                'html'       => view('trips._route_section', ['day' => $day])->render(),
                'update_url' => route('routes.update', $route),
                'stops'      => $stops,
                'mode'       => $route->transport_mode,
                'message'    => __('messages.route.updated'),
            ]);
        }

        return back()->with('success', __('messages.route.updated'));
    }

    public function destroy(DayRoute $route)
    {
        $route->delete();
        return back()->with('success', __('messages.route.removed'));
    }
}
