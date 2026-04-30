@extends('layouts.tenant')

@section('title', 'Invoices')

@section('content')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
    <a href="{{ route('invoices.create') }}"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        + New Invoice
    </a>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->client_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($invoice->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full font-medium
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->due_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No invoices found. <a href="{{ route('invoices.create') }}" class="text-indigo-600">Create your first invoice</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $invoices->links() }}
</div>
@endsection
