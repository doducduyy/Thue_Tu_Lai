<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:car,motorcycle'],
            'segment' => ['required', 'in:sedan,suv,hatchback,van,scooter,motorcycle,electric-motorcycle'],
            'model_year' => ['nullable', 'integer', 'between:1990,2100'],
            'license_plate' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'license_plate')->ignore($this->route('vehicle')),
            ],
            'image' => ['nullable', 'image', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:8'],
            'gallery.*' => ['image', 'max:5120'],
            'seat_count' => ['required', 'integer', 'min:1', 'max:100'],
            'transmission' => ['required', 'in:automatic,manual'],
            'fuel_type' => ['required', 'in:gasoline,diesel,electric'],
            'engine_capacity_cc' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'fuel_consumption' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'price_per_day' => ['required', 'integer', 'min:0'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:1,2,3'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
