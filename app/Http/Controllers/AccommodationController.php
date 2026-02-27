<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Destination;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'required|string',
            'address'           => 'nullable|string|max:255',
            'check_in'          => 'nullable|string|max:50',
            'check_out'         => 'nullable|string|max:50',
            'confirmation_code' => 'nullable|string|max:100',
            'price_per_night'   => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'url'               => 'nullable|url|max:500',
            'notes'             => 'nullable|string',
        ]);

        $destination->accommodations()->create($data);
        return back()->with('success', __('messages.accommodation.added'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'type'              => 'required|string',
            'address'           => 'nullable|string|max:255',
            'check_in'          => 'nullable|string|max:50',
            'check_out'         => 'nullable|string|max:50',
            'confirmation_code' => 'nullable|string|max:100',
            'price_per_night'   => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'url'               => 'nullable|url|max:500',
            'notes'             => 'nullable|string',
        ]);

        $accommodation->update($data);
        return back()->with('success', __('messages.accommodation.updated'));
    }

    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return back()->with('success', __('messages.accommodation.removed'));
    }
}
