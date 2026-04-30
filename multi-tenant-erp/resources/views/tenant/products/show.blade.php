@extends('layouts.tenant')

@section('title', $product->name)

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <dl class="grid grid-cols-2 gap-6">
            <div>
                <dt class="text-sm text-gray-500">SKU</dt>
                <dd class="mt-1 text-lg font-mono font-medium text-gray-900">{{ $product->sku }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Price</dt>
                <dd class="mt-1 text-lg font-medium text-gray-900">${{ number_format($product->price, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Quantity in Stock</dt>
                <dd class="mt-1">
                    <span class="inline-flex px-3 py-1 text-sm rounded-full font-medium
                        {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $product->quantity }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Category</dt>
                <dd class="mt-1 text-gray-900">{{ $product->category ?? 'Uncategorized' }}</dd>
            </div>
            @if($product->description)
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500">Description</dt>
                    <dd class="mt-1 text-gray-900">{{ $product->description }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-sm text-gray-500">Added</dt>
                <dd class="mt-1 text-gray-900">{{ $product->created_at->format('F d, Y') }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
