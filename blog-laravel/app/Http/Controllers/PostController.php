<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    // Homepage - all posts (public)
    public function index()
    {
        $posts = Post::latest()
            ->with('user')
            ->get();

        return view('posts.index', compact('posts'));
    }

    // Sirf logged-in user ke posts
    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())
            ->latest()
            ->with('user')
            ->get();

        return view('posts.my', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    // Store new post with Cloudinary upload
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4048',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        if ($request->hasFile('image')) {
            // Upload to Cloudinary using facade
            $imageUrl = Cloudinary::upload(
                $request->file('image')->getRealPath()
            )->getSecurePath();

            $data['image_path'] = $imageUrl;
        }

        $data['user_id'] = Auth::id();

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->with('user')
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    public function edit(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (!Auth::user()->is_admin && $post->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (!Auth::user()->is_admin && $post->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this post.');
        }

        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4048',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        if ($request->hasFile('image')) {
            $imageUrl = Cloudinary::upload(
                $request->file('image')->getRealPath()
            )->getSecurePath();

            $data['image_path'] = $imageUrl;
        }

        $post->update($data);

        return redirect()
            ->route('posts.show', ['slug' => $post->slug])
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (!Auth::user()->is_admin && $post->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to delete this post.');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
