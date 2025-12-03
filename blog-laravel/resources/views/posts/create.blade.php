@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4">Create New Post</h1>

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

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}"
                required
            >
            @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        {{-- Featured Image + Preview --}}
        <div class="mb-3">
            <label for="image" class="form-label">Featured Image (optional)</label>
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

            {{-- Live preview (hidden until file selected) --}}
            <div id="create-image-preview-wrapper" class="mt-2 d-none">
                <small class="text-muted d-block mb-1">Image Preview</small>
                <img id="create-image-preview"
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
            >{{ old('body') }}</textarea>
            @error('body')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save Post</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>

    {{-- JS: live image preview on create --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput  = document.getElementById('image');
            const wrapper    = document.getElementById('create-image-preview-wrapper');
            const previewImg = document.getElementById('create-image-preview');

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
