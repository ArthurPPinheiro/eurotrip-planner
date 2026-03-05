<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function store(Day $day, Request $request)
    {
        $data = $request->validate([
            'departure_airport'  => 'required|string|max:100',
            'arrival_airport'    => 'required|string|max:100',
            'flight_number'      => 'nullable|string|max:20',
            'airline'            => 'nullable|string|max:100',
            'locator'            => 'nullable|string|max:20',
            'departure_city'     => 'nullable|string|max:100',
            'arrival_city'       => 'nullable|string|max:100',
            'departure_time'     => 'nullable|string|max:10',
            'arrival_time'       => 'nullable|string|max:10',
            'duration_minutes'   => 'nullable|integer|min:0',
            'seat'               => 'nullable|string|max:10',
            'cabin_class'        => 'nullable|in:economy,premium_economy,business,first',
            'notes'              => 'nullable|string|max:1000',
        ]);

        $flight = $day->flights()->create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'html'       => view('trips._flight_card', ['flight' => $flight])->render(),
                'modal_html' => view('trips._flight_edit_modal', ['flight' => $flight, 'day' => $day])->render(),
                'message'    => __('messages.flight.added'),
            ]);
        }

        return back()->with('success', __('messages.flight.added'));
    }

    public function update(Flight $flight, Request $request)
    {
        $data = $request->validate([
            'departure_airport'  => 'required|string|max:100',
            'arrival_airport'    => 'required|string|max:100',
            'flight_number'      => 'nullable|string|max:20',
            'airline'            => 'nullable|string|max:100',
            'locator'            => 'nullable|string|max:20',
            'departure_city'     => 'nullable|string|max:100',
            'arrival_city'       => 'nullable|string|max:100',
            'departure_time'     => 'nullable|string|max:10',
            'arrival_time'       => 'nullable|string|max:10',
            'duration_minutes'   => 'nullable|integer|min:0',
            'seat'               => 'nullable|string|max:10',
            'cabin_class'        => 'nullable|in:economy,premium_economy,business,first',
            'notes'              => 'nullable|string|max:1000',
        ]);

        $flight->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'html'    => view('trips._flight_card', ['flight' => $flight])->render(),
                'flight'  => $flight->toArray(),
                'message' => __('messages.flight.updated'),
            ]);
        }

        return back()->with('success', __('messages.flight.updated'));
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();

        return back()->with('success', __('messages.flight.removed'));
    }
}
