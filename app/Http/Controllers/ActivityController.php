<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function store(Request $request, Destination $destination) {
        $request->validate([
            'type' => 'required|in:hotel,poi,reservation,comment',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'link' => 'nullable|url',
            'time' => 'nullable|string',
            'price' => 'nullable|numeric',
            'currency' => 'nullable|string',
        ]);

        $order = $destination->activities()->max('order') + 1;
        Activity::create(array_merge($request->only('type', 'title', 'description', 'address', 'link', 'time', 'price', 'currency'), [
            'destination_id' => $destination->id,
            'added_by' => Auth::id(),
            'order' => $order,
        ]));

        return back()->with('success', ucfirst($request->type) . ' added!');
    }

    public function destroy(Activity $activity) {
        $activity->delete();
        return back()->with('success', 'Removed.');
    }
}
