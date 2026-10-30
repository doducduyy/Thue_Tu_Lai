<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\ServiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['contract.user', 'vehicle'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $contracts = Contract::with(['user', 'vehicle'])
            ->where('status', 1)
            ->whereDoesntHave('invoices')
            ->get();

        return view('invoices.create', compact('contracts'));
    }

    public function createStep2(Request $request)
    {
        $data = $request->validate([
            'contract_id' => ['required', 'exists:contracts,id'],
            'invoice_date' => ['required', 'date'],
            'status' => ['required', 'in:0,1'],
        ]);
        $contract = Contract::with(['vehicle', 'user'])->findOrFail($data['contract_id']);

        if ((int) $contract->status !== 1 || Invoice::where('contract_id', $contract->id)->exists()) {
            throw ValidationException::withMessages([
                'contract_id' => 'Hợp đồng không còn đủ điều kiện lập hóa đơn.',
            ]);
        }

        $rentalDays = $this->rentalDays($contract);
        $serviceDetails = $this->contractServices($contract);
        $vehicleAmount = $rentalDays * (float) $contract->vehicle->price_per_day;
        $serviceTotal = $serviceDetails->sum(function (ServiceDetail $detail) use ($rentalDays) {
            if (!$detail->service) {
                return 0;
            }
            $quantity = (float) $detail->quantity;
            if ($this->isDailyService($detail)) {
                $quantity *= $rentalDays;
            }

            return $quantity * (float) $detail->service->unit_price;
        });

        return view('invoices.create_table', [
            'contract' => $contract,
            'invoiceDate' => $data['invoice_date'],
            'status' => $data['status'],
            'rentalDays' => $rentalDays,
            'vehicleAmount' => $vehicleAmount,
            'serviceTotal' => $serviceTotal,
            'serviceDetails' => $serviceDetails,
        ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with([
            'contract.vehicle',
            'contract.user',
            'invoiceDetails.service',
        ])->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contract_id' => ['required', 'exists:contracts,id'],
            'invoice_date' => ['required', 'date'],
            'status' => ['required', 'in:0,1'],
        ]);
        $contract = Contract::with('vehicle')->findOrFail($data['contract_id']);

        if ((int) $contract->status !== 1 || Invoice::where('contract_id', $contract->id)->exists()) {
            throw ValidationException::withMessages([
                'contract_id' => 'Hợp đồng không còn đủ điều kiện lập hóa đơn.',
            ]);
        }

        $rentalDays = $this->rentalDays($contract);
        $vehicleAmount = $rentalDays * (float) $contract->vehicle->price_per_day;
        $serviceDetails = $this->contractServices($contract);

        $details = $serviceDetails
            ->filter(fn (ServiceDetail $detail) => $detail->service !== null)
            ->map(function (ServiceDetail $detail) use ($rentalDays) {
                $quantity = (float) $detail->quantity;
                if ($this->isDailyService($detail)) {
                    $quantity *= $rentalDays;
                }

                $price = (float) $detail->service->unit_price;
                return [
                    'service_id' => $detail->service->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $quantity * $price,
                ];
            });
        $serviceFee = $details->sum('amount');

        DB::transaction(function () use ($data, $contract, $rentalDays, $vehicleAmount, $serviceFee, $details) {
            if (Invoice::where('contract_id', $contract->id)->lockForUpdate()->exists()) {
                throw ValidationException::withMessages([
                    'contract_id' => 'Hợp đồng này đã có hóa đơn.',
                ]);
            }

            $invoice = Invoice::create([
                'contract_id' => $contract->id,
                'vehicle_id' => $contract->vehicle_id,
                'invoice_date' => $data['invoice_date'],
                'rental_days' => $rentalDays,
                'vehicle_amount' => $vehicleAmount,
                'service_fee' => $serviceFee,
                'total_amount' => $vehicleAmount + $serviceFee,
                'status' => $data['status'],
            ]);

            foreach ($details as $detail) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    ...$detail,
                ]);
            }
        });

        return Redirect::route('invoices.index')->with('success', 'Đã xuất hóa đơn thành công!');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate(['status' => ['required', 'in:0,1']]);
        Invoice::findOrFail($id)->update(['status' => $data['status']]);

        return redirect()->route('invoices.index')->with('success', 'Cập nhật trạng thái hóa đơn thành công!');
    }

    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $invoice->invoiceDetails()->delete();
            $invoice->delete();
        });

        return Redirect::route('invoices.index')->with('success', 'Đã xóa hóa đơn thành công!');
    }

    public function confirmPayment($id)
    {
        Invoice::findOrFail($id)->update(['status' => 1]);

        return redirect()->back()->with('success', 'Xác nhận thanh toán thành công!');
    }

    private function rentalDays(Contract $contract): int
    {
        $minutes = \Carbon\Carbon::parse($contract->start_date)
            ->diffInMinutes(\Carbon\Carbon::parse($contract->end_date));

        return max(1, (int) ceil($minutes / 1440));
    }

    private function isDailyService(ServiceDetail $detail): bool
    {
        return in_array(
            mb_strtolower((string) $detail->service->unit_name),
            ['ngày', 'day', 'ngày thuê'],
            true
        );
    }

    private function contractServices(Contract $contract)
    {
        if ($contract->services()->exists()) {
            return $contract->services()->get()->map(function ($service) {
                $service->setAttribute('unit_price', $service->pivot->unit_price);
                $detail = new ServiceDetail([
                    'service_id' => $service->id,
                    'quantity' => $service->pivot->quantity,
                ]);
                $detail->setRelation('service', $service);

                return $detail;
            });
        }

        return ServiceDetail::with('service')
            ->where('vehicle_id', $contract->vehicle_id)
            ->get();
    }
}
