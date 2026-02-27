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

    public function store(Request $request, Trip $trip)
    {
        $this->authorize("view", $trip);
        $request->validate([
            "type" => "required|in:passport,visa,insurance,ticket,other",
            "title" => "required|string|max:255",
            "file" => "required|file|max:10240",
            "expires_at" => "nullable|date",
            "notes" => "nullable|string",
        ]);

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
