<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;   // PHP SDK

class PostController extends Controller
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        // CLOUDINARY_URL env se config load
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    public function index()
    {
        $posts = Post::latest()->with('user')->get();
        return view('posts.index', compact('posts'));
    }

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4048',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();

        if ($request->hasFile('image')) {
            $upload = $this->cloudinary
                ->uploadApi()
                ->upload(
                    $request->file('image')->getRealPath(),
                    ['folder' => 'laravel-blog/posts']
                );

            $data['image_path'] = $upload['secure_url'] ?? null;
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
            $upload = $this->cloudinary
                ->uploadApi()
                ->upload(
                    $request->file('image')->getRealPath(),
                    ['folder' => 'laravel-blog/posts']
                );

            $data['image_path'] = $upload['secure_url'] ?? null;
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
