@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">All Posts</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">+ New Post</a>
        @endauth
    </div> 

    @if ($posts->count())
        @foreach ($posts as $post) 
            <div class="card mb-3">
                @if (!empty($post->image_path))
                    <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body">
                    <h2 class="h5 mb-2">
                        <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <p class="text-muted mb-1">
                        {{ $post->created_at->format('d M Y, h:i A') }}
                    </p>
                    <p class="mb-0">
                        {{ Str::limit(strip_tags(html_entity_decode($post->body)), 150) }}
                    </p>
                    <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="mt-2 d-inline-block">
                        Read more →
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            No posts found. Create your first post!
        </div>
    @endif
@endsection
