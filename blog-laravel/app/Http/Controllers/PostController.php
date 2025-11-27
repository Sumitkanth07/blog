<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Homepage - all posts
    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
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
],[
    'image.max' => 'Image size must not exceed 2MB.',
]);
    $data['slug'] = \Illuminate\Support\Str::slug($data['title']) . '-' . time();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('posts', 'public');
        $data['image_path'] = $path;
    }

    \App\Models\Post::create($data);

    return redirect()
        ->route('posts.index')
        ->with('success', 'Post created successfully!');
}


    // Show single post by slug
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('posts.show', compact('post'));
    }
    // Show edit form
public function edit(string $slug)
{
    $post = Post::where('slug', $slug)->firstOrFail();

    return view('posts.edit', compact('post'));
}

// Update post
public function update(Request $request, string $slug)
{
    $post = Post::where('slug', $slug)->firstOrFail();

    $data = $request->validate([
        'title' => 'required|max:255',
        'body'  => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // slug ko update karna hai ya nahi?  
    // Abhi ke liye change karenge, taki title change ho to URL bhi update ho jaye
    $data['slug'] = Str::slug($data['title']) . '-' . time();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('posts', 'public');
        $data['image_path'] = $path;
    }

    $post->update($data);

    return redirect()
        ->route('posts.show', ['slug' => $post->slug])
        ->with('success', 'Post updated successfully!');
}

// Delete post
public function destroy(string $slug)
{
    $post = Post::where('slug', $slug)->firstOrFail();
    $post->delete();

    return redirect()
        ->route('posts.index')
        ->with('success', 'Post deleted successfully!');
}

}
