@extends('layouts.app')

@section('title', 'Verify Email - ShortURL')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center py-12 px-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md text-center">
        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Verify Your Email</h1>
        <p class="text-gray-500 mb-6">
            We've sent a verification link to your email address. Please check your inbox and click the link to verify your account.
        </p>

        @if (session('resent'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-green-700">A fresh verification link has been sent to your email address.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button
                type="submit"
                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-semibold text-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg"
            >
                Resend Verification Email
            </button>
        </form>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="text-gray-500 hover:text-indigo-600">
                Sign out and try again
            </button>
        </form>
    </div>
</div>
@endsection
