@extends('layouts.welcome')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h2 class="text-4xl font-bold mb-4">
                        {{ __('welcome.welcome', ['app' => config('app.name')]) }}
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                        {{ __('welcome.subtitle') }}
                    </p>
                    <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="text-sm text-gray-700 dark:text-gray-500 underline">{{ __('auth.login') }}</a>
                        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">{{ __('auth.register') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
