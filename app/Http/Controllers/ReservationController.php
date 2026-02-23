<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|string',
            'datetime'          => 'nullable|date',
            'venue'             => 'nullable|string|max:255',
            'address'           => 'nullable|string|max:255',
            'confirmation_code' => 'nullable|string|max:100',
            'price'             => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'notes'             => 'nullable|string',
        ]);

        $destination->reservations()->create($data);
        return back()->with('success', 'Reservation added!');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|string',
            'datetime'          => 'nullable|date',
            'venue'             => 'nullable|string|max:255',
            'address'           => 'nullable|string|max:255',
            'confirmation_code' => 'nullable|string|max:100',
            'price'             => 'nullable|numeric|min:0',
            'currency'          => 'nullable|string|max:10',
            'notes'             => 'nullable|string',
        ]);

        $reservation->update($data);
        return back()->with('success', 'Updated!');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Removed.');
    }
}
