<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $type, int $id)
    {
        $data = $request->validate([
            'author_name' => 'required|string|max:100',
            'content'     => 'required|string',
        ]);

        $modelMap = [
            'destination' => \App\Models\Destination::class,
            'poi'         => \App\Models\PointOfInterest::class,
            'accommodation' => \App\Models\Accommodation::class,
            'reservation' => \App\Models\Reservation::class,
        ];

        abort_unless(isset($modelMap[$type]), 404);
        $model = $modelMap[$type]::findOrFail($id);
        $model->comments()->create($data);

        return back()->with('success', 'Comment added!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment removed.');
    }
}
