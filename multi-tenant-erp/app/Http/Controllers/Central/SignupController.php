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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $tenant = Tenant::create([
            'id' => $request->subdomain,
            'name' => $request->company_name,
        ]);

        $tenant->domains()->create([
            'domain' => $request->subdomain,
        ]);

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
        $tenantUrl = $scheme . '://' . $request->subdomain . '.localhost' . $portSuffix;

        return redirect($tenantUrl . '/login')
            ->with('status', 'Your workspace is ready! Please log in.');
    }
}
