<?php

namespace App\Http\Controllers;

use App\Models\ServiceDetail;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ServiceDetailController extends Controller
{
    public function index()
    {
        $allDetails = ServiceDetail::with(['vehicle', 'service'])->get();

        $groupedServiceDetails = $allDetails->groupBy(function ($item) {
            return $item->vehicle->name ?? 'Xe không tồn tại';
        })->sortKeys();

        return view('serviceDetails.index', compact('groupedServiceDetails'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $services = Service::all();
        return view('serviceDetails.create', compact('vehicles', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_id' => ['required', 'exists:services,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
        ]);
        ServiceDetail::updateOrCreate(
            ['vehicle_id' => $data['vehicle_id'], 'service_id' => $data['service_id']],
            ['quantity' => $data['quantity']]
        );

        return redirect()->route('serviceDetails.index');
    }

    public function edit($vehicle_id, $service_id)
    {
        $serviceDetail = ServiceDetail::where('vehicle_id', $vehicle_id)
            ->where('service_id', $service_id)
            ->firstOrFail();

        $vehicles = Vehicle::all();
        $services = Service::all();

        return view('serviceDetails.edit', compact('serviceDetail', 'vehicles', 'services'));
    }

    public function update(Request $request, $vehicle_id, $service_id)
    {
        $detail = ServiceDetail::where('vehicle_id', $vehicle_id)
            ->where('service_id', $service_id)
            ->firstOrFail();
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_id' => [
                'required',
                'exists:services,id',
                Rule::unique('service_details', 'service_id')
                    ->where(fn ($query) => $query->where('vehicle_id', $request->input('vehicle_id')))
                    ->ignore($detail->id),
            ],
            'quantity' => ['required', 'numeric', 'min:0.01'],
        ]);
        $detail->update($data);

        return redirect()->route('serviceDetails.index');
    }

    public function destroy($vehicle_id, $service_id)
    {
        ServiceDetail::where('vehicle_id', $vehicle_id)
            ->where('service_id', $service_id)
            ->delete();

        return Redirect::route('serviceDetails.index');
    }
}
