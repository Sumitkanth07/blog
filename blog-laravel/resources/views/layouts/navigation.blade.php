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
                        @endauth

                    <li class="nav-item">
                        <form method="POST" action="/logout">
                            @csrf

                            <x-dropdown-link href="/logout"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
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
