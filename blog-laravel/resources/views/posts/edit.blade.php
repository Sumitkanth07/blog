@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4">Edit Post</h1>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', ['slug' => $post->slug]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $post->title) }}"
                required
            >
            @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        @if ($post->image_path)
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="img-fluid rounded mb-2" style="max-height: 200px;">
            </div>
        @endif

        <div class="mb-3">
            <label for="image" class="form-label">Change Image (optional)</label>
            <input
                type="file"
                id="image"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
                accept="image/*"
            >
            @error('image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Content</label>
            <textarea
                id="body"
                name="body"
                rows="6"
                class="form-control rich-text @error('body') is-invalid @enderror"
                required
            >{{ old('body', $post->body) }}</textarea>
            @error('body')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Post</button>
        <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
@endsection
