<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SignupController extends Controller
{
    public function showForm()
    {
        return view('central.signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => [
                'required', 'string', 'alpha_dash', 'max:63', 'min:3',
                Rule::notIn(['www', 'api', 'admin', 'mail', 'ftp', 'app']),
                Rule::unique('domains', 'domain'),
            ],
            'custom_domain' => [
                'nullable', 'string', 'max:255',
                'regex:/^([a-z0-9]([a-z0-9\-]*[a-z0-9])?\.)+[a-z]{2,}$/',
                Rule::notIn(config('tenancy.central_domains', [])),
                Rule::unique('domains', 'domain'),
            ],
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
        ], [
            'custom_domain.regex' => 'Please enter a valid domain name (e.g., erp.yourdomain.com).',
            'custom_domain.unique' => 'This domain is already in use.',
        ]);

        $tenant = Tenant::create([
            'id' => $request->subdomain,
            'name' => $request->company_name,
        ]);

        $tenant->domains()->create([
            'domain' => $request->subdomain,
            'type' => 'subdomain',
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        if ($request->filled('custom_domain')) {
            $tenant->domains()->create([
                'domain' => strtolower($request->custom_domain),
                'type' => 'custom',
                'verification_status' => 'pending',
            ]);
        }

        $tenant->run(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('admin');
        });

        $port = $request->getPort();
        $portSuffix = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
        $scheme = $request->getScheme();
        $platformDomain = config('app.platform_domain', 'localhost');
        $tenantUrl = $scheme . '://' . $request->subdomain . '.' . $platformDomain . $portSuffix;

        return redirect($tenantUrl . '/login')
            ->with('status', 'Your workspace is ready! Please log in.');
    }
}
