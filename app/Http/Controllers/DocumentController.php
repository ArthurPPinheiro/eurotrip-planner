<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Trip $trip)
    {
        $this->authorize("view", $trip);
        $documents = $trip
            ->documents()
            ->with("owner")
            ->orderBy("type")
            ->orderBy("created_at", "desc")
            ->get();
        $grouped = $documents->groupBy("type");
        return view("documents.index", compact("trip", "documents", "grouped"));
    }

    const MAX_FILE_SIZE_KB = 5120;       // 5 MB per file
    const MAX_TRIP_STORAGE_KB = 51200;   // 50 MB total per trip
    const MAX_TRIP_FILES = 20;           // max documents per trip

    public function store(Request $request, Trip $trip)
    {
        $this->authorize("view", $trip);
        $request->validate([
            "type" => "required|in:passport,visa,insurance,ticket,other",
            "title" => "required|string|max:255",
            "file" => "required|file|mimes:pdf,jpeg,jpg,png,webp|max:" . self::MAX_FILE_SIZE_KB,
            "expires_at" => "nullable|date",
            "notes" => "nullable|string|max:1000",
        ]);

        $totalUsed = $trip->documents()->sum("size");
        if ($totalUsed + ($request->file("file")->getSize()) > self::MAX_TRIP_STORAGE_KB * 1024) {
            return back()->withErrors(["file" => __("messages.document.quota_exceeded")])->withInput();
        }

        if ($trip->documents()->count() >= self::MAX_TRIP_FILES) {
            return back()->withErrors(["file" => __("messages.document.limit_reached")])->withInput();
        }

        $file = $request->file("file");
        $path = $file->store("trips/{$trip->id}/documents", "r2");

        Document::create([
            "trip_id" => $trip->id,
            "user_id" => Auth::id(),
            "type" => $request->type,
            "title" => $request->title,
            "file_path" => $path,
            "original_name" => $file->getClientOriginalName(),
            "mime_type" => $file->getMimeType(),
            "size" => $file->getSize(),
            "expires_at" => $request->expires_at,
            "notes" => $request->notes,
        ]);

        return back()->with("success", __("messages.document.uploaded"));
    }

    public function download(Document $document)
    {
        $file = Storage::disk("r2")->get($document->file_path);

        return response($file, 200, [
            "Content-Type" => Storage::disk("r2")->mimeType(
                $document->file_path,
            ),
            "Content-Disposition" =>
                'attachment; filename="' . $document->original_name . '"',
        ]);
    }

    public function destroy(Document $document)
    {
        Storage::disk("r2")->delete($document->file_path);
        $document->delete();
        return back()->with("success", __("messages.document.deleted"));
    }
}
