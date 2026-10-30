<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class VehicleController extends Controller
{
    public function home(Request $request)
    {
        $this->validateSearchDates($request);
        $vehicles = $this->filterCatalog($this->availableVehicles($request), $request)
            ->latest()
            ->take(6)
            ->get();

        return view('home', [
            'vehicles' => $vehicles,
            'services' => \App\Models\Service::all(),
        ]);
    }

    public function index(Request $request)
    {
        $this->validateSearchDates($request);
        $query = $this->filterCatalog(
            $this->availableVehicles($request),
            $request
        );
        if ($request->input('sort') === 'price_asc') {
            $query->orderBy('price_per_day');
        } elseif ($request->input('sort') === 'price_desc') {
            $query->orderByDesc('price_per_day');
        } else {
            $query->latest();
        }

        $vehicles = $query->paginate(12)->withQueryString();
        $brands = Vehicle::query()->where('status', '!=', 3)->distinct()->orderBy('brand')->pluck('brand');
        $segments = Vehicle::query()->where('status', '!=', 3)->whereNotNull('segment')->distinct()->orderBy('segment')->pluck('segment');

        return view('vehicles.index', compact('vehicles', 'brands', 'segments'));
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        $services = \App\Models\Service::orderBy('name')->get();

        return view('vehicles.show', compact('vehicle', 'services'));
    }

    public function adminIndex(Request $request)
    {
        $query = Vehicle::query()->withCount('contracts');
        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('license_plate', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->integer('status'));
        }
        $vehicles = $query->latest()->paginate(12)->withQueryString();
        $statusCounts = Vehicle::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $totalVehicles = Vehicle::count();

        return view('admin.vehicles.index', compact('vehicles', 'statusCounts', 'totalVehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('Images', 'public');
        }
        $data['gallery'] = collect($request->file('gallery', []))
            ->map(fn ($image) => $image->store('Images', 'public'))
            ->all();
        Vehicle::create($data);
        return Redirect::route('admin.vehicles.index');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($vehicle->image) {
                Storage::disk('public')->delete($this->normalizeImagePath($vehicle->image));
            }
            $data['image'] = $request->file('image')->store('Images', 'public');
        }
        if ($request->hasFile('gallery')) {
            $data['gallery'] = collect($request->file('gallery'))
                ->map(fn ($image) => $image->store('Images', 'public'))
                ->merge($vehicle->gallery ?? [])
                ->all();
        } else {
            unset($data['gallery']);
        }

        $vehicle->update($data);
        return Redirect::route('admin.vehicles.index');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->contracts()->exists()) {
            return Redirect::back()->with('error', 'Không thể xóa xe đã có hợp đồng thuê.');
        }

        if ($vehicle->image) {
            Storage::disk('public')->delete($this->normalizeImagePath($vehicle->image));
        }
        $vehicle->delete();
        if (Vehicle::count() == 0) {
            DB::statement('ALTER TABLE vehicles AUTO_INCREMENT = 1');
        }
        return Redirect::route('admin.vehicles.index');
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^(?:/?storage/)+#', '', $path);

        return preg_replace('#^(Images/)+#', 'Images/', ltrim($path, '/'));
    }

    private function availableVehicles(Request $request)
    {
        $query = Vehicle::query()->where('status', '!=', 3);
        if ($request->filled('pickup_at') && $request->filled('return_at')) {
            $pickup = \Carbon\Carbon::parse($request->input('pickup_at'));
            $return = \Carbon\Carbon::parse($request->input('return_at'));
            $query->whereDoesntHave('contracts', function ($contracts) use ($pickup, $return) {
                $contracts->where('status', 1)
                    ->where('start_date', '<', $return)
                    ->where('end_date', '>', $pickup);
            });
        } else {
            $query->where('status', 1);
        }

        return $query;
    }

    private function validateSearchDates(Request $request): void
    {
        $request->validate([
            'pickup_at' => ['nullable', 'date'],
            'return_at' => ['nullable', 'date', 'after:pickup_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'in:car,motorcycle'],
            'segment' => ['nullable', 'in:sedan,suv,hatchback,van,scooter,motorcycle,electric-motorcycle'],
            'price_max' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:recommended,price_asc,price_desc'],
        ]);
    }

    private function filterCatalog($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }
        foreach (['brand', 'category', 'segment'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }
        if ($request->filled('location')) {
            $query->where('pickup_location', 'like', '%' . $request->input('location') . '%');
        }
        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->integer('price_max'));
        }

        return $query;
    }
}
