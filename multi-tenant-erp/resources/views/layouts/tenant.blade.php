<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ tenant('name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-indigo-900 text-white min-h-screen fixed">
            <div class="p-6">
                <h2 class="text-lg font-bold truncate">{{ tenant('name') }}</h2>
                <p class="text-indigo-300 text-xs mt-1">{{ request()->getHost() }}</p>
            </div>
            <nav class="mt-2">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-6 py-3 text-sm hover:bg-indigo-800 transition {{ request()->routeIs('dashboard') ? 'bg-indigo-800 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('invoices.index') }}"
                   class="flex items-center px-6 py-3 text-sm hover:bg-indigo-800 transition {{ request()->routeIs('invoices.*') ? 'bg-indigo-800 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Invoices
                </a>
                <a href="{{ route('products.index') }}"
                   class="flex items-center px-6 py-3 text-sm hover:bg-indigo-800 transition {{ request()->routeIs('products.*') ? 'bg-indigo-800 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Products
                </a>
                <a href="{{ route('domain.settings') }}"
                   class="flex items-center px-6 py-3 text-sm hover:bg-indigo-800 transition {{ request()->routeIs('domain.*') ? 'bg-indigo-800 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    Domain Settings
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-indigo-800">
                <div class="flex items-center justify-between">
                    <span class="text-sm truncate">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-indigo-300 hover:text-white text-xs">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="ml-64 flex-1 p-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
