<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;
    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'brand',
        'category',
        'segment',
        'model_year',
        'license_plate',
        'image',
        'gallery',
        'seat_count',
        'transmission',
        'fuel_type',
        'engine_capacity_cc',
        'fuel_consumption',
        'price_per_day',
        'pickup_location',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'model_year' => 'integer',
            'engine_capacity_cc' => 'integer',
            'fuel_consumption' => 'decimal:2',
            'seat_count' => 'integer',
            'price_per_day' => 'integer',
            'status' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = str_replace('\\', '/', $this->image);
        $path = preg_replace('#^(?:/?storage/)+#', '', $path);
        $path = preg_replace('#^(Images/)+#', 'Images/', ltrim($path, '/'));

        return Storage::url($path);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'vehicle_id');
    }

    public function serviceDetails()
    {
        return $this->hasMany(ServiceDetail::class, 'vehicle_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'vehicle_id');
    }
}
