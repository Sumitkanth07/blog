@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Latest Posts</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">+ New Post</a>
        @endauth
    </div>

    @if ($posts->count())
        @foreach ($posts as $post)
            <div class="card mb-3 home-post-card">
                <div class="row g-0">
                    @if (!empty($post->image_path))
                        <div class="col-md-4">
                            <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">
                                <img src="{{ asset('storage/' . $post->image_path) }}"
                                     class="home-post-thumb"
                                     alt="{{ $post->title }}">
                            </a>
                        </div>
                        <div class="col-md-8">
                    @else
                        <div class="col-12">
                    @endif
                            <div class="card-body">
                                <h2 class="h5 mb-2">
                                    <a href="{{ route('posts.show', ['slug' => $post->slug]) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                <p class="text-muted mb-1 small">
                                    {{ $post->created_at->format('d M Y, h:i A') }}
                                </p>

                                <p class="mb-2">
                                    {{ Str::limit(strip_tags(html_entity_decode($post->body)), 180) }}
                                </p>

                                <a href="{{ route('posts.show', ['slug' => $post->slug]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Read more →
                                </a>
                            </div>
                        </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">No posts found. Create your first post!</div>
    @endif
@endsection
