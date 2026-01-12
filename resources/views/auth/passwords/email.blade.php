@extends('layouts.app')

@section('title', 'Reset Password - ShortURL')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center py-12 px-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Reset Password</h1>
        <p class="text-gray-500 text-center mb-8">Enter your email to receive a reset link</p>

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-green-700">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
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

            <button
                type="submit"
                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-semibold text-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg"
            >
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Back to login</a>
        </p>
    </div>
</div>
@endsection
