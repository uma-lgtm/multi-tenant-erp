@extends('layouts.tenant')

@section('title', 'Domain Settings')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Domain Settings</h1>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Current Domains --}}
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Your Domains</h2>
            <p class="text-sm text-gray-500 mt-1">Manage the domains that point to your workspace.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($domains as $domain)
                        <tr>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-gray-900">
                                    @if($domain->type === 'subdomain')
                                        {{ $domain->domain }}.{{ $platformDomain }}
                                    @else
                                        {{ $domain->domain }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($domain->type === 'subdomain')
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                        Primary Subdomain
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                        Custom Domain
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($domain->verification_status === 'verified')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Verified
                                    </span>
                                @elseif($domain->verification_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($domain->isCustom())
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('domain.verify', $domain->id) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Verify
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('domain.destroy', $domain->id) }}" onsubmit="return confirm('Remove this domain?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Default</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Custom Domain --}}
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Add Custom Domain</h2>
            <p class="text-sm text-gray-500 mt-1">Connect your own domain to access this workspace.</p>
        </div>
        <form method="POST" action="{{ route('domain.store') }}" class="p-6">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="custom_domain" value="{{ old('custom_domain') }}"
                           placeholder="erp.yourdomain.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm">
                    @error('custom_domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">
                    Add Domain
                </button>
            </div>
        </form>
    </div>

    {{-- DNS Instructions --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h3 class="text-sm font-semibold text-blue-900">How to connect your custom domain</h3>
                <div class="mt-3 text-sm text-blue-800 space-y-3">
                    <p>After adding your domain above, configure one of the following DNS records with your domain registrar (GoDaddy, Cloudflare, Namecheap, etc.):</p>

                    <div class="bg-white/60 rounded-lg p-4 space-y-3">
                        <div>
                            <p class="font-semibold">Option 1: CNAME Record (recommended for subdomains)</p>
                            <p class="mt-1">Use this for domains like <code class="bg-white px-1.5 py-0.5 rounded text-xs font-mono">erp.yourdomain.com</code></p>
                            <div class="mt-2 bg-gray-900 text-green-400 rounded-lg p-3 font-mono text-xs">
                                Type: CNAME<br>
                                Name: erp (or your subdomain)<br>
                                Target: <span class="text-yellow-300">{{ $platformDomain }}</span>
                            </div>
                        </div>

                        <div class="border-t border-blue-200 pt-3">
                            <p class="font-semibold">Option 2: A Record (required for root/apex domains)</p>
                            <p class="mt-1">Use this for domains like <code class="bg-white px-1.5 py-0.5 rounded text-xs font-mono">yourdomain.com</code></p>
                            <div class="mt-2 bg-gray-900 text-green-400 rounded-lg p-3 font-mono text-xs">
                                Type: A<br>
                                Name: @ (or leave blank)<br>
                                Value: <span class="text-yellow-300">{{ $serverIp }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-blue-700">DNS changes can take up to <strong>24-48 hours</strong> to propagate. Once configured, click the <strong>Verify</strong> button next to your domain.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
