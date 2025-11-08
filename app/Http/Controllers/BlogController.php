<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // GET /api/dashboard/blog
    public function index()
    {
        $blogs = Blog::latest()->get();

        return response()->json([
            'message' => 'Data blog berhasil diambil',
            'data' => $blogs
        ], 200);
    }

    // GET /api/dashboard/blog/{id}
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

    // POST /api/dashboard/blog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|max:5000',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'slug' => 'required|string|max:255|unique:blogs',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $blog = Blog::create($validated);

        return response()->json([
            'message' => 'Blog berhasil disimpan!',
            'data' => $blog,
        ], 201);
    }

    // PUT /api/dashboard/blog/{id}
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|max:5000',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image_url) {
                $oldPath = str_replace('/storage/', '', $blog->image_url);
                \Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $blog->update($validated);

        return response()->json([
            'message' => 'Blog berhasil diperbarui',
            'data' => $blog,
        ]);
    }

    // DELETE /api/dashboard/blog/{id}
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Data blog tidak ditemukan'
            ], 404);
        }

        // hapus gambar jika ada
        if ($blog->image_url) {
            $oldPath = str_replace('/storage/', '', $blog->image_url);
            \Storage::disk('public')->delete($oldPath);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Data blog berhasil dihapus'
        ], 200);
    }
}
