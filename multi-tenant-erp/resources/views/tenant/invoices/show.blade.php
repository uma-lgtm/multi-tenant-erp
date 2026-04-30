@extends('layouts.tenant')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('invoices.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
        <span class="inline-flex px-3 py-1 text-sm rounded-full font-medium
            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
            {{ ucfirst($invoice->status) }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <dl class="grid grid-cols-2 gap-6">
            <div>
                <dt class="text-sm text-gray-500">Client</dt>
                <dd class="mt-1 text-lg font-medium text-gray-900">{{ $invoice->client_name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Amount</dt>
                <dd class="mt-1 text-lg font-medium text-gray-900">${{ number_format($invoice->amount, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Due Date</dt>
                <dd class="mt-1 text-gray-900">{{ $invoice->due_date->format('F d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Created</dt>
                <dd class="mt-1 text-gray-900">{{ $invoice->created_at->format('F d, Y') }}</dd>
            </div>
            @if($invoice->notes)
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500">Notes</dt>
                    <dd class="mt-1 text-gray-900">{{ $invoice->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>
@endsection
