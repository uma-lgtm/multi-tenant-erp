<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);

        return view('tenant.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('tenant.invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number|max:50',
            'client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Invoice::create($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        return view('tenant.invoices.show', compact('invoice'));
    }
}
