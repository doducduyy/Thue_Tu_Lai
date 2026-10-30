<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetail;
use App\Models\Service;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class InvoiceDetailController extends Controller
{
    public function index()
    {
        $invoiceDetails = InvoiceDetail::all();
        return view('invoiceDetails.index', compact('invoiceDetails'));
    }

    public function create()
    {
        $services = Service::all();
        $invoices = Invoice::all();
        return view('invoiceDetails.create', compact('services', 'invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'service_id' => [
                'required',
                'exists:services,id',
                Rule::unique('invoice_details', 'service_id')
                    ->where(fn ($query) => $query->where('invoice_id', $request->input('invoice_id'))),
            ],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        $invoiceDetail = InvoiceDetail::create([
            ...$data,
            'amount' => $data['quantity'] * $data['price'],
        ]);
        $this->syncInvoiceTotals((int) $invoiceDetail->invoice_id);
        return Redirect::route('invoiceDetails.index');
    }

    public function edit($service_id, $invoice_id)
    {
        $invoiceDetail = InvoiceDetail::where('service_id', $service_id)
            ->where('invoice_id', $invoice_id)
            ->firstOrFail();

        $services = Service::all();
        $invoices = Invoice::all();

        return view('invoiceDetails.edit', compact('invoiceDetail', 'services', 'invoices'));
    }

    public function update(Request $request, $service_id, $invoice_id)
    {
        $data = $request->validate([
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
        InvoiceDetail::where('service_id', $service_id)
            ->where('invoice_id', $invoice_id)
            ->firstOrFail();
        InvoiceDetail::where('service_id', $service_id)
            ->where('invoice_id', $invoice_id)
            ->update([...$data, 'amount' => $data['quantity'] * $data['price']]);
        $this->syncInvoiceTotals((int) $invoice_id);

        return Redirect::route('invoiceDetails.index');
    }

    public function destroy($service_id, $invoice_id)
    {
        InvoiceDetail::where('service_id', $service_id)
            ->where('invoice_id', $invoice_id)
            ->delete();
        $this->syncInvoiceTotals((int) $invoice_id);

        return Redirect::route('invoiceDetails.index');
    }

    private function syncInvoiceTotals(int $invoiceId): void
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $serviceFee = InvoiceDetail::where('invoice_id', $invoiceId)->sum('amount');
        $invoice->update([
            'service_fee' => $serviceFee,
            'total_amount' => (float) $invoice->vehicle_amount + (float) $serviceFee,
        ]);
    }
}
