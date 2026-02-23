<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\PointOfInterest;
use Illuminate\Http\Request;

class PointOfInterestController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'address'     => 'nullable|string|max:255',
            'url'         => 'nullable|url|max:500',
        ]);

        $data['order'] = $destination->pointsOfInterest()->count();
        $destination->pointsOfInterest()->create($data);

        return back()->with('success', 'Point of interest added!');
    }

    public function update(Request $request, PointOfInterest $pointOfInterest)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'address'     => 'nullable|string|max:255',
            'url'         => 'nullable|url|max:500',
            'visited'     => 'sometimes|boolean',
        ]);

        $pointOfInterest->update($data);
        return back()->with('success', 'Updated!');
    }

    public function toggle(PointOfInterest $pointOfInterest)
    {
        $pointOfInterest->update(['visited' => !$pointOfInterest->visited]);
        return back();
    }

    public function destroy(PointOfInterest $pointOfInterest)
    {
        $pointOfInterest->delete();
        return back()->with('success', 'Removed.');
    }
}
