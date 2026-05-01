@extends('layouts.central')

@section('title', 'Multi-Tenant ERP Platform')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-20">
    <div class="text-center">
        <h1 class="text-5xl font-extrabold text-gray-900 leading-tight">
            Your Business, <span class="text-indigo-600">Your Workspace</span>
        </h1>
        <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto">
            A multi-tenant ERP platform where each company gets its own isolated workspace with dedicated database, subdomain, and modules.
        </p>
        <div class="mt-10">
            <a href="/signup"
               class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white text-lg font-semibold rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-xl transition">
                Get Started Free
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>

    {{-- Features --}}
    <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-xl p-8 shadow-sm border">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Data Isolation</h3>
            <p class="mt-2 text-gray-600">Each tenant gets a dedicated database. Your data never mixes with others.</p>
        </div>
        <div class="bg-white rounded-xl p-8 shadow-sm border">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Accounting & Invoicing</h3>
            <p class="mt-2 text-gray-600">Create and manage invoices, track payments, and monitor your finances.</p>
        </div>
        <div class="bg-white rounded-xl p-8 shadow-sm border">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Inventory Management</h3>
            <p class="mt-2 text-gray-600">Track products, manage stock levels, and organize your warehouse.</p>
        </div>
    </div>

    {{-- How it works --}}
    <div class="mt-24 text-center">
        <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto text-lg font-bold">1</div>
                <h3 class="mt-4 font-semibold text-gray-900">Sign Up</h3>
                <p class="mt-2 text-gray-600">Choose your company name and subdomain</p>
            </div>
            <div>
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto text-lg font-bold">2</div>
                <h3 class="mt-4 font-semibold text-gray-900">Auto-Provisioned</h3>
                <p class="mt-2 text-gray-600">Your workspace and database are created instantly</p>
            </div>
            <div>
                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto text-lg font-bold">3</div>
                <h3 class="mt-4 font-semibold text-gray-900">Start Working</h3>
                <p class="mt-2 text-gray-600">Access your ERP at your-company.{{ config('app.platform_domain', 'localhost') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
