@extends('layouts.central')

@section('title', 'Create Your Workspace')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow-sm border p-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Create Your Workspace</h2>
        <p class="mt-2 text-gray-600 text-center text-sm">Get your own ERP workspace in seconds</p>

        <form method="POST" action="{{ route('signup.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Acme Corp">
                @error('company_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subdomain" class="block text-sm font-medium text-gray-700">Choose Your Subdomain</label>
                <div class="mt-1 flex rounded-lg shadow-sm">
                    <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                           class="block w-full rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="acme">
                    <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        .localhost
                    </span>
                </div>
                @error('subdomain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-6">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Your Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="John Doe">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="john@acme.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Min 8 characters">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Repeat password">
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 shadow transition">
                Create Workspace
            </button>
        </form>
    </div>
</div>
@endsection
