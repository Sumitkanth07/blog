@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('posts.index') }}" class="btn btn-link p-0">&larr; Back to all posts</a>

        {{-- Edit / Delete => Only Owner or Admin --}}
        @auth
            @if(auth()->id() === $post->user_id || (auth()->user()->is_admin ?? false))
                <div>
                    <a href="{{ route('posts.edit', ['slug' => $post->slug]) }}" class="btn btn-sm btn-primary">
                        Edit
                    </a>

                    <form action="{{ route('posts.destroy', ['slug' => $post->slug]) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    <div class="card">
        <div class="card-body">
            <h1 class="h3 mb-2">{{ $post->title }}</h1>

            <p class="text-muted mb-3">
                @if($post->user ?? false)
                    By <strong>{{ $post->user->name }}</strong> ·
                @endif
                Published on {{ $post->created_at->format('d M Y, h:i A') }}
            </p>

            {{-- Cloudinary Image Display (UPDATED) --}}
            @if (!empty($post->image_path))
                <img src="{{ $post->image_path }}"
                     class="img-fluid mb-3 rounded"
                     alt="{{ $post->title }}">
            @endif

            <div style="white-space: pre-line;">
                {!! $post->body !!}
            </div>
        </div>
    </div>
@endsection
