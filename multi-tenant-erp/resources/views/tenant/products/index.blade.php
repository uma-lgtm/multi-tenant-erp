@extends('layouts.tenant')

@section('title', 'Products')

@section('content')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
    <a href="{{ route('products.create') }}"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        + Add Product
    </a>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full font-medium
                            {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $product->quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No products found. <a href="{{ route('products.create') }}" class="text-indigo-600">Add your first product</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
