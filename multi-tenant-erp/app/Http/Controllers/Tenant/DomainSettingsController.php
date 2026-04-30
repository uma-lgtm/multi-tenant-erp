<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DomainSettingsController extends Controller
{
    public function index()
    {
        $domains = tenant()->domains()->orderBy('type')->get();

        return view('tenant.domain-settings', [
            'domains' => $domains,
            'platformDomain' => config('app.platform_domain', 'localhost'),
            'serverIp' => config('app.server_ip', '127.0.0.1'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'custom_domain' => [
                'required',
                'string',
                'max:255',
                'regex:/^([a-z0-9]([a-z0-9\-]*[a-z0-9])?\.)+[a-z]{2,}$/',
                Rule::notIn(config('tenancy.central_domains', [])),
                'unique:central.domains,domain',
            ],
        ], [
            'custom_domain.regex' => 'Please enter a valid domain name (e.g., erp.yourdomain.com).',
            'custom_domain.unique' => 'This domain is already in use.',
        ]);

        tenant()->domains()->create([
            'domain' => strtolower($request->custom_domain),
            'type' => 'custom',
            'verification_status' => 'pending',
        ]);

        return redirect()->route('domain.settings')
            ->with('success', 'Custom domain added. Please configure your DNS and then click Verify.');
    }

    public function verify($domainId)
    {
        $domain = Domain::findOrFail($domainId);

        // Ensure this domain belongs to the current tenant
        if ($domain->tenant_id !== tenant('id')) {
            abort(403);
        }

        // Only verify custom domains
        if (!$domain->isCustom()) {
            return redirect()->route('domain.settings')
                ->with('error', 'Subdomain records do not need verification.');
        }

        // Check if the domain resolves to our server
        $verified = $this->checkDns($domain->domain);

        if ($verified) {
            $domain->markAsVerified();
            return redirect()->route('domain.settings')
                ->with('success', "Domain '{$domain->domain}' has been verified successfully!");
        }

        $domain->markAsFailed();
        return redirect()->route('domain.settings')
            ->with('error', "DNS verification failed for '{$domain->domain}'. Please check your DNS records and try again. DNS changes can take up to 48 hours to propagate.");
    }

    public function destroy($domainId)
    {
        $domain = Domain::findOrFail($domainId);

        // Ensure this domain belongs to the current tenant
        if ($domain->tenant_id !== tenant('id')) {
            abort(403);
        }

        // Never allow deleting the subdomain
        if (!$domain->isCustom()) {
            return redirect()->route('domain.settings')
                ->with('error', 'You cannot remove your primary subdomain.');
        }

        $domainName = $domain->domain;
        $domain->delete();

        return redirect()->route('domain.settings')
            ->with('success', "Domain '{$domainName}' has been removed.");
    }

    private function checkDns(string $domain): bool
    {
        $expectedIp = config('app.server_ip', '127.0.0.1');
        $platformDomain = config('app.platform_domain', 'localhost');

        // Check CNAME record
        $cnameRecords = @dns_get_record($domain, DNS_CNAME);
        if ($cnameRecords) {
            foreach ($cnameRecords as $record) {
                if (isset($record['target']) && rtrim($record['target'], '.') === $platformDomain) {
                    return true;
                }
            }
        }

        // Check A record
        $resolvedIp = @gethostbyname($domain);
        if ($resolvedIp !== $domain && $resolvedIp === $expectedIp) {
            return true;
        }

        return false;
    }
}
