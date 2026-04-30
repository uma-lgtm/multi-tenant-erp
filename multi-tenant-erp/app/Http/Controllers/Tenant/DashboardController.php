<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        return view('tenant.dashboard', [
            'tenantName' => tenant('name'),
            'tenantId' => tenant('id'),
            'invoiceCount' => Invoice::count(),
            'productCount' => Product::count(),
            'recentInvoices' => Invoice::latest()->take(5)->get(),
            'recentProducts' => Product::latest()->take(5)->get(),
        ]);
    }
}
