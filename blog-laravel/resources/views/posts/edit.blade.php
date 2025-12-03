@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

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

        {{-- Title --}}
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

        {{-- CURRENT IMAGE (SMALL THUMBNAIL) --}}
        @if ($post->image_path)
            @php
                $path = ltrim($post->image_path, '/');

                // Agar Cloudinary / external URL hai to as-it-is use karo
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    $imageUrl = $path;
                } else {
                    // Local storage wali image
                    $imageUrl = asset('storage/' . $path);
                }
            @endphp

            <div class="mb-3">
                <label class="form-label d-block">Current Image</label>

                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $post->title }}"
                    class="img-thumbnail mb-2"
                    style="max-width: 200px; max-height: 200px; object-fit: cover;"
                >
        @endif

        {{-- CHANGE IMAGE + LIVE PREVIEW --}}
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

            {{-- New Image Preview (hidden until user selects file) --}}
            <div id="new-image-preview-wrapper" class="mt-2 d-none">
                <small class="text-muted d-block mb-1">New Image Preview</small>
                <img id="new-image-preview"
                     class="img-thumbnail"
                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
            </div>
        </div>

        {{-- Content --}}
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

    {{-- SIMPLE JS FOR LIVE IMAGE PREVIEW --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput  = document.getElementById('image');
            const wrapper    = document.getElementById('new-image-preview-wrapper');
            const previewImg = document.getElementById('new-image-preview');

            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    const file = this.files && this.files[0];

                    if (!file) {
                        wrapper.classList.add('d-none');
                        previewImg.removeAttribute('src');
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    previewImg.src = url;
                    wrapper.classList.remove('d-none');
                });
            }
        });
    </script>
@endsection
