<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Day;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function index()
    {
        $trips = Auth::user()
            ->trips()
            ->with("creator", "members")
            ->orderByRaw("CASE WHEN start_date IS NULL THEN 1 ELSE 0 END")
            ->orderBy("start_date", "asc")
            ->get();
        return view("trips.index", compact("trips"));
    }

    public function create()
    {
        return view("trips.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "start_date" => "nullable|date",
            "end_date" => "nullable|date|after_or_equal:start_date",
        ]);

        $trip = Trip::create([
            "name" => $request->name,
            "description" => $request->description,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "created_by" => Auth::id(),
        ]);

        $trip->members()->attach(Auth::id(), ["role" => "owner"]);

        // Auto-create days if dates provided
        if ($request->start_date && $request->end_date) {
            $start = \Carbon\Carbon::parse($request->start_date);
            $end = \Carbon\Carbon::parse($request->end_date);
            $dayNum = 1;
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                Day::create([
                    "trip_id" => $trip->id,
                    "date" => $d->toDateString(),
                    "day_number" => $dayNum++,
                ]);
            }
        }

        return redirect()
            ->route("trips.show", $trip)
            ->with("success", __("messages.trip.created"));
    }

    public function show(Trip $trip)
    {
        $this->authorize("view", $trip);
        $trip->load(["days.destinations.activities.author", "days.route.stops", "days.flights", "members"]);
        return view("trips.show", compact("trip"));
    }

    public function edit(Trip $trip)
    {
        $this->authorize("update", $trip);
        return view("trips.edit", compact("trip"));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorize("update", $trip);
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "start_date" => "nullable|date",
            "end_date" => "nullable|date|after_or_equal:start_date",
        ]);
        $trip->update(
            $request->only("name", "description", "start_date", "end_date"),
        );
        return redirect()
            ->route("trips.show", $trip)
            ->with("success", __("messages.trip.updated"));
    }

    public function destroy(Trip $trip)
    {
        $this->authorize("delete", $trip);
        $trip->delete();
        return redirect()
            ->route("trips.index")
            ->with("success", __("messages.trip.deleted"));
    }

    public function join(Request $request)
    {
        $request->validate(["invite_code" => "required|string"]);
        $trip = Trip::where(
            "invite_code",
            strtoupper($request->invite_code),
        )->firstOrFail();
        if (!$trip->members()->where("user_id", Auth::id())->exists()) {
            $trip->members()->attach(Auth::id(), ["role" => "member"]);
        }
        return redirect()
            ->route("trips.show", $trip)
            ->with("success", __("messages.trip.joined"));
    }

    public function addDay(Trip $trip)
    {
        $this->authorize("view", $trip);
        $lastDay = $trip->days()->max("day_number") ?? 0;
        $lastDate = $trip->days()->max("date");
        $newDate = $lastDate
            ? \Carbon\Carbon::parse($lastDate)->addDay()
            : now();
        Day::create([
            "trip_id" => $trip->id,
            "date" => $newDate->toDateString(),
            "day_number" => $lastDay + 1,
        ]);
        return back()->with("success", __("messages.day.added"));
    }
}
