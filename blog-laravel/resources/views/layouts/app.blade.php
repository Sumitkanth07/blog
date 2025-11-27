<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Blog - Laravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .page-wrapper {
            max-width: 900px;
            margin: 30px auto 40px;
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        footer {
            margin-top: 40px;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }
        .nav-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('posts.index') }}">My Blog</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="{{ route('posts.index') }}" class="nav-link">Home</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a href="{{ route('posts.create') }}" class="nav-link">Create Post</a>
                    </li>

                    <li class="nav-item d-flex align-items-center ms-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="nav-avatar">
                        @endif

                        <span class="text-light me-2">
                            {{ auth()->user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Sign Up</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="page-wrapper">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<footer>
    <div class="container">
        <span>Simple Blog &copy; {{ date('Y') }}</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- TinyMCE CDN (v5, no key required) --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: 'textarea.rich-text',
            menubar: false,
            plugins: 'lists link linkchecker code',
            toolbar: 'undo redo | styles | bold italic underline | bullist numlist | link | code',
            height: 300,
            branding: false,
            setup: function (editor) {
                // jab bhi content change ho, textarea ko sync kar do
                editor.on('change', function () {
                    editor.save();
                });
            }
        });

        // Form submit hone se pehle sab editor ka content textarea me daal do
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
            });
        });
    });
</script>



</body>
</html>
