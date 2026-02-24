<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get("/register", [AuthController::class, "showRegister"])->name(
    "register",
);
Route::post("/register", [AuthController::class, "register"]);
Route::get("/login", [AuthController::class, "showLogin"])->name("login");
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

// Protected
Route::middleware("auth")->group(function () {
    Route::get("/", [TripController::class, "index"])->name("trips.home");

    // Trips
    Route::get("/trips", [TripController::class, "index"])->name("trips.index");
    Route::get("/trips/create", [TripController::class, "create"])->name(
        "trips.create",
    );
    Route::post("/trips", [TripController::class, "store"])->name(
        "trips.store",
    );
    Route::get("/trips/{trip}", [TripController::class, "show"])->name(
        "trips.show",
    );
    Route::get("/trips/{trip}/edit", [TripController::class, "edit"])->name(
        "trips.edit",
    );
    Route::put("/trips/{trip}", [TripController::class, "update"])->name(
        "trips.update",
    );
    Route::delete("/trips/{trip}", [TripController::class, "destroy"])->name(
        "trips.destroy",
    );
    Route::post("/trips/join", [TripController::class, "join"])->name(
        "trips.join",
    );
    Route::post("/trips/{trip}/days", [TripController::class, "addDay"])->name(
        "trips.addDay",
    );

    //Days
    Route::delete("/days/{day}", [DayController::class, "destroy"])->name(
        "days.destroy",
    );

    // Destinations
    Route::post("/days/{day}/destinations", [
        DestinationController::class,
        "store",
    ])->name("destinations.store");
    Route::delete("/destinations/{destination}", [
        DestinationController::class,
        "destroy",
    ])->name("destinations.destroy");

    // Activities
    Route::post("/destinations/{destination}/activities", [
        ActivityController::class,
        "store",
    ])->name("activities.store");
    Route::delete("/activities/{activity}", [
        ActivityController::class,
        "destroy",
    ])->name("activities.destroy");

    // Documents
    Route::get("/trips/{trip}/documents", [
        DocumentController::class,
        "index",
    ])->name("documents.index");
    Route::post("/trips/{trip}/documents", [
        DocumentController::class,
        "store",
    ])->name("documents.store");
    Route::get("/documents/{document}/download", [
        DocumentController::class,
        "download",
    ])->name("documents.download");
    Route::delete("/documents/{document}", [
        DocumentController::class,
        "destroy",
    ])->name("documents.destroy");
});
