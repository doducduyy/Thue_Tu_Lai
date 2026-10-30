<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['user', 'vehicle'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $users = User::where('role', 0)->get();
        $vehicles = Vehicle::where('status', '!=', 3)->get();

        return view('contracts.create', compact('users', 'vehicles'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedContract($request);
        $this->createContract($data, []);

        return Redirect::route('contracts.index');
    }

    public function book(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'distinct', 'exists:services,id'],
        ]);
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $data['user_id'] = $request->user()->id;
        $data['deposit'] = 0;
        $data['status'] = 1;
        $serviceIds = $data['service_ids'] ?? [];
        unset($data['service_ids']);

        $contract = $this->createContract($data, $serviceIds);

        return Redirect::route('customer.contract')
            ->with('success', 'Đã gửi yêu cầu đặt ' . $vehicle->name . '.');
    }

    public function edit(Contract $contract)
    {
        $users = User::where('role', 0)->get();
        $vehicles = Vehicle::where('status', 1)
            ->orWhereKey($contract->vehicle_id)
            ->get();

        return view('contracts.edit', compact('contract', 'users', 'vehicles'));
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $this->validatedContract($request);
        $data['deposit'] = str_replace('.', '', (string) $data['deposit']);

        DB::transaction(function () use ($contract, $data) {
            $oldVehicleId = $contract->vehicle_id;
            $newVehicle = Vehicle::whereKey($data['vehicle_id'])->lockForUpdate()->firstOrFail();
            if ((int) $newVehicle->status === 3) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Xe đang bảo dưỡng, vui lòng chọn xe khác.',
                ]);
            }
            $this->assertAvailable($newVehicle, $data['start_date'], $data['end_date'], $contract->id);

            $contract->update($data);
            if ((int) $oldVehicleId !== (int) $data['vehicle_id']) {
                $hasOtherActiveContract = Contract::where('vehicle_id', $oldVehicleId)
                    ->where('status', 1)
                    ->where('id', '!=', $contract->id)
                    ->exists();
                if (!$hasOtherActiveContract) {
                    Vehicle::whereKey($oldVehicleId)->update(['status' => 1]);
                }
            }
            $newVehicle->update([
                'status' => $newVehicle->status === 3 ? 3 : ($this->isCurrentlyRented($newVehicle) ? 2 : 1),
            ]);
        });

        return Redirect::route('contracts.index');
    }

    public function destroy(Contract $contract)
    {
        DB::transaction(function () use ($contract) {
            $vehicleId = $contract->vehicle_id;
            $contract->delete();
            $vehicle = Vehicle::find($vehicleId);
            if ($vehicle && $vehicle->status !== 3) {
                $vehicle->update(['status' => $this->isCurrentlyRented($vehicle) ? 2 : 1]);
            }

            if (Contract::count() === 0) {
                DB::statement('ALTER TABLE contracts AUTO_INCREMENT = 1');
            }
        });

        return Redirect::route('contracts.index');
    }

    private function validatedContract(Request $request): array
    {
        $request->merge([
            'deposit' => str_replace('.', '', (string) $request->input('deposit')),
        ]);

        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'deposit' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:0,1'],
        ]);
    }

    private function createContract(array $data, array $serviceIds): Contract
    {
        return DB::transaction(function () use ($data, $serviceIds) {
            $vehicle = Vehicle::whereKey($data['vehicle_id'])->lockForUpdate()->firstOrFail();
            if ((int) $vehicle->status === 3) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Xe đang bảo dưỡng, vui lòng chọn xe khác.',
                ]);
            }
            $this->assertAvailable($vehicle, $data['start_date'], $data['end_date']);

            $data['start_date'] = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
            $data['end_date'] = Carbon::parse($data['end_date'])->format('Y-m-d H:i:s');
            $contract = Contract::create($data);
            if ($serviceIds) {
                $services = Service::whereIn('id', $serviceIds)->get();
                $contract->services()->sync($services->mapWithKeys(fn (Service $service) => [
                    $service->id => ['quantity' => 1, 'unit_price' => $service->unit_price],
                ]));
            }
            $vehicle->update(['status' => $this->isCurrentlyRented($vehicle) ? 2 : 1]);

            return $contract;
        });
    }

    private function assertAvailable(Vehicle $vehicle, string $startDate, string $endDate, ?int $ignoreContractId = null): void
    {
        $overlap = Contract::where('vehicle_id', $vehicle->id)
            ->where('status', 1)
            ->where('start_date', '<', Carbon::parse($endDate)->format('Y-m-d H:i:s'))
            ->where('end_date', '>', Carbon::parse($startDate)->format('Y-m-d H:i:s'));
        if ($ignoreContractId) {
            $overlap->where('id', '!=', $ignoreContractId);
        }
        if ($overlap->exists()) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Xe đã có lịch thuê trùng với khoảng thời gian đã chọn.',
            ]);
        }
    }

    private function isCurrentlyRented(Vehicle $vehicle): bool
    {
        $now = now();

        return Contract::where('vehicle_id', $vehicle->id)
            ->where('status', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now)
            ->exists();
    }
}
