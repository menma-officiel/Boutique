<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Comment::with('product')->latest();

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function($w) use ($q) {
                $w->where('author_name', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%");
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('approved')) {
            $approved = (int) $request->input('approved');
            $query->where('approved', $approved);
        }

        $comments = $query->paginate(20)->appends($request->only(['search','product_id','approved']));

        $products = \App\Models\Product::select('id','name')->orderBy('name')->get();

        return view('admin.comments.index', compact('comments','products'));
    }

    /**
     * Bulk actions for comments (approve/disapprove/delete)
     */
    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => 'required|string|in:approve,disapprove,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:comments,id',
        ]);

        $ids = $data['ids'];

        if ($data['action'] === 'approve') {
            Comment::whereIn('id', $ids)->update(['approved' => 1]);
            $message = 'Commentaires approuvés.';
        } elseif ($data['action'] === 'disapprove') {
            Comment::whereIn('id', $ids)->update(['approved' => 0]);
            $message = 'Commentaires désapprouvés.';
        } else {
            Comment::whereIn('id', $ids)->delete();
            $message = 'Commentaires supprimés.';
        }

        return redirect()->route('admin.comments.index')->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return redirect()->route('admin.comments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'approved' => 'required|boolean',
        ]);

        $comment->approved = $data['approved'];
        $comment->save();

        return redirect()->route('admin.comments.index')->with('success', 'Commentaire mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Commentaire supprimé.');
    }
}
