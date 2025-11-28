<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Google Login --}}
    <div class="mt-6">
        <a
            href="{{ route('google.redirect') }}"
            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium
                   rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                   focus:ring-red-500"
        >
            <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.22l6.9-6.9C35.82 2.56 30.24 0 24 0 14.62 0 6.51 5.38 2.69 13.22l8.03 6.23C12.67 12.06 17.9 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.5 24c0-1.53-.15-3.01-.41-4.44H24v8.52h12.7c-.55 2.95-2.12 5.49-4.47 7.19l7.05 5.46C43.81 36.75 46.5 30.78 46.5 24z"/>
                <path fill="#FBBC04" d="M10.72 28.45C9.97 26.48 9.5 24.29 9.5 22c0-2.29.47-4.48 1.21-6.45L2.69 13.22C.98 17.07 0 20.95 0 24c0 3.05.98 6.93 2.69 10.78l8.03-6.33z"/>
                <path fill="#34A853" d="M24 48c6.24 0 11.82-2.56 15.93-6.82l-7.05-5.46c-2.04 1.35-4.78 2.34-8.88 2.34-6.1 0-11.33-3.56-13.28-8.95l-8.03 6.33C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Continue with Google
        </a>
    </div>

    {{-- OR Divider --}}
    <div class="mt-6 flex items-center justify-center">
        <span class="text-gray-500 text-sm">or continue with email</span>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                Create Account
            </a>

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
