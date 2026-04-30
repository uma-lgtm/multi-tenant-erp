@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-900">Welcome to {{ $tenantName }}</h1>
    <p class="text-gray-600 mt-1">Subdomain: <span class="font-mono text-indigo-600">{{ $tenantId }}.localhost</span></p>
</div>

{{-- Stats Cards --}}
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('invoices.index') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Invoices</p>
                <p class="text-3xl font-bold text-gray-900">{{ $invoiceCount }}</p>
            </div>
        </div>
    </a>

    <a href="{{ route('products.index') }}" class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-900">{{ $productCount }}</p>
            </div>
        </div>
    </a>
</div>

{{-- Recent Activity --}}
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Recent Invoices --}}
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-900">Recent Invoices</h3>
            <a href="{{ route('invoices.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">+ New</a>
        </div>
        <div class="p-6">
            @forelse($recentInvoices as $invoice)
                <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs text-gray-500">{{ $invoice->client_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">${{ number_format($invoice->amount, 2) }}</p>
                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">No invoices yet. <a href="{{ route('invoices.create') }}" class="text-indigo-600">Create one</a></p>
            @endforelse
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="bg-white rounded-xl shadow-sm border">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-900">Recent Products</h3>
            <a href="{{ route('products.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">+ New</a>
        </div>
        <div class="p-6">
            @forelse($recentProducts as $product)
                <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">${{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-gray-500">Qty: {{ $product->quantity }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">No products yet. <a href="{{ route('products.create') }}" class="text-indigo-600">Add one</a></p>
            @endforelse
        </div>
    </div>
</div>
@endsection
