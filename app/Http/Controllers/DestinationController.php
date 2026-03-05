<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function store(Request $request, Day $day) {
        $request->validate(['city' => 'required|string', 'country' => 'required|string', 'emoji' => 'nullable|string']);
        $order = $day->destinations()->max('order') + 1;
        $dest = Destination::create([
            'day_id' => $day->id,
            'trip_id' => $day->trip_id,
            'city' => $request->city,
            'country' => $request->country,
            'emoji' => $request->emoji ?? '🌍',
            'order' => $order,
        ]);

        if ($request->wantsJson()) {
            $dest->setRelation('activities', collect([]));
            return response()->json([
                'html' => view('trips._destination_card', ['dest' => $dest])->render(),
                'message' => __('messages.city.added'),
            ]);
        }

        return back()->with('success', __('messages.city.added'));
    }

    public function destroy(Destination $destination) {
        $destination->delete();
        return back()->with('success', __('messages.destination.removed'));
    }
}
