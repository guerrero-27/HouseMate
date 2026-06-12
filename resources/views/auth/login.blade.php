<x-guest-layout>
    <h2 class="text-3xl font-bold text-gray-800 mb-1">Welcome back</h2>
    <p class="text-gray-500 text-sm mb-8">Sign in to your account to continue</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs text-indigo-600 hover:text-indigo-500 font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>
            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 ease-in-out shadow-sm">
            Sign in
        </button>

        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Register</a>
        </p>
    </form>
</x-guest-layout>
