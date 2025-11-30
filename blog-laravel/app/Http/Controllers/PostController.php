<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Homepage - all posts (public)
    public function index()
    {
        // Public: sab posts dikh sakti hain
        $posts = Post::latest()
            ->with('user')   // $post->user->name use karne ke liye
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

    // Show create form (only for logged-in users - auth middleware in routes)
    public function create()
    {
        return view('posts.create');
    }

    // Store new post
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'image.max' => 'Image size must not exceed 2MB.',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = $path;
        }

        // Jis user ne login kiya hai, wohi owner hoga
        $data['user_id'] = Auth::id();

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    // Show single post by slug (public)
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->with('user')
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    // Show edit form - only owner or admin
    public function edit(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Agar current user admin nahi hai aur owner bhi nahi -> 403
        if (!Auth::user()->is_admin && $post->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    // Update post - only owner or admin
    public function update(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if (!Auth::user()->is_admin && $post->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this post.');
        }

        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = $path;
        }

        // yahan user_id change nahi kar rahe
        $post->update($data);

        return redirect()
            ->route('posts.show', ['slug' => $post->slug])
            ->with('success', 'Post updated successfully!');
    }

    // Delete post - only owner or admin
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
