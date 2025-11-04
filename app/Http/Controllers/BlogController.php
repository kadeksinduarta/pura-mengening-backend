<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // GET /api/blog
    public function index()
    {
        $blogs = Blog::latest()->get();

        return response()->json([
            'message' => 'Data blog berhasil diambil',
            'data' => $blogs
        ], 200);
    }

    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ], 200);
    }

    // POST /api/blog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|max:5000',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'image_url' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs',
        ]);

        $blog = Blog::create($validated);

        return response()->json([
            'message' => 'Blog berhasil disimpan!',
            'data' => $blog,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        $blog->update($request->all());
        return response()->json(['message' => 'Blog berhasil diperbarui', 'data' => $blog]);
    }

    // DELETE /api/blog/{id}
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Data blog tidak ditemukan'
            ], 404);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Data blog berhasil dihapus'
        ], 200);
    }
}
