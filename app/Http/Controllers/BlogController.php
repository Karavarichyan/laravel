<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
     /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        $blogs = Blog::with('user')->latest()->paginate(10);
        return response()->json($blogs);
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('blogs') : null;

        $blog = Blog::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
        ]);

        return response()->json($blog, 201);
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        // Check if the user is authorized to update the blog
        $this->authorize('update', $blog);

        $request->validate([
            'title' => 'sometimes|required|max:255',
            'content' => 'sometimes|required',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs');
            $blog->update(['image' => $path]);
        }

        $blog->update($request->only('title', 'content'));

        return response()->json($blog);
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        // Check if the user is authorized to delete the blog
        $this->authorize('delete', $blog);

        $blog->delete();

        return response()->json(null, 204);
    }
}
