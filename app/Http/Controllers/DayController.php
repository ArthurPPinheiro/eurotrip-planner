<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Trip;
use Illuminate\Http\Request;

class DayController extends Controller
{
    public function store(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'date'  => 'required|date',
            'label' => 'nullable|string|max:255',
        ]);

        $data['order'] = $trip->days()->count();
        $trip->days()->create($data);

        return back()->with('success', 'Day added!');
    }

    public function update(Request $request, Day $day)
    {
        $data = $request->validate([
            'date'  => 'required|date',
            'label' => 'nullable|string|max:255',
        ]);

        $day->update($data);
        return back()->with('success', 'Day updated!');
    }

    public function destroy(Day $day)
    {
        $trip = $day->trip;
        $day->delete();
        return back()->with('success', 'Day removed.');
    }
}
