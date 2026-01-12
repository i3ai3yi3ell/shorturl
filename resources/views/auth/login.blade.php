@extends('layouts.app')

@section('title', 'Login - ShortURL')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center py-12 px-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Welcome Back</h1>
        <p class="text-gray-500 text-center mb-8">Sign in to your account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-600 text-sm">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-indigo-600 text-sm hover:underline">
                    Forgot password?
                </a>
            </div>

            <button
                type="submit"
                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-semibold text-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg"
            >
                Sign In
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection
