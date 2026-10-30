<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'name' => 'Toyota Vios 2024',
                'brand' => 'Toyota',
                'category' => 'car',
                'segment' => 'sedan',
                'model_year' => 2024,
                'license_plate' => '51G-123.45',
                'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 4,
                'transmission' => 'automatic',
                'fuel_type' => 'gasoline',
                'engine_capacity_cc' => 1500,
                'fuel_consumption' => 5.8,
                'price_per_day' => 700000,
                'pickup_location' => 'Quận 1, TP. Hồ Chí Minh',
                'description' => 'Sedan tiết kiệm nhiên liệu, phù hợp đi trong thành phố.',
            ],
            [
                'name' => 'Honda City 2023',
                'brand' => 'Honda',
                'category' => 'car',
                'segment' => 'sedan',
                'model_year' => 2023,
                'license_plate' => '51H-234.56',
                'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 4,
                'transmission' => 'automatic',
                'fuel_type' => 'gasoline',
                'engine_capacity_cc' => 1500,
                'fuel_consumption' => 5.6,
                'price_per_day' => 800000,
                'pickup_location' => 'Quận 3, TP. Hồ Chí Minh',
                'description' => 'Sedan rộng rãi, tiện nghi cho gia đình.',
            ],
            [
                'name' => 'Ford Everest 2024',
                'brand' => 'Ford',
                'category' => 'car',
                'segment' => 'suv',
                'model_year' => 2024,
                'license_plate' => '51K-345.67',
                'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 7,
                'transmission' => 'automatic',
                'fuel_type' => 'diesel',
                'engine_capacity_cc' => 2000,
                'fuel_consumption' => 8.5,
                'price_per_day' => 1800000,
                'pickup_location' => 'Thủ Đức, TP. Hồ Chí Minh',
                'description' => 'SUV 7 chỗ phù hợp cho những chuyến đi xa.',
            ],
            [
                'name' => 'Honda SH 150i 2024',
                'brand' => 'Honda',
                'category' => 'motorcycle',
                'segment' => 'scooter',
                'model_year' => 2024,
                'license_plate' => '59S1-456.78',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 2,
                'transmission' => 'automatic',
                'fuel_type' => 'gasoline',
                'engine_capacity_cc' => 157,
                'fuel_consumption' => 2.4,
                'price_per_day' => 350000,
                'pickup_location' => 'Quận 1, TP. Hồ Chí Minh',
                'description' => 'Xe tay ga cao cấp, tiện lợi di chuyển trong thành phố.',
            ],
            [
                'name' => 'Honda Vision 2024',
                'brand' => 'Honda',
                'category' => 'motorcycle',
                'segment' => 'scooter',
                'model_year' => 2024,
                'license_plate' => '59S2-567.89',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 2,
                'transmission' => 'automatic',
                'fuel_type' => 'gasoline',
                'engine_capacity_cc' => 110,
                'fuel_consumption' => 1.9,
                'price_per_day' => 180000,
                'pickup_location' => 'Quận 5, TP. Hồ Chí Minh',
                'description' => 'Xe tay ga nhỏ gọn và tiết kiệm nhiên liệu.',
            ],
            [
                'name' => 'Mazda 3 2023',
                'brand' => 'Mazda',
                'category' => 'car',
                'segment' => 'sedan',
                'model_year' => 2023,
                'license_plate' => '51L-678.90',
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=85',
                'seat_count' => 4,
                'transmission' => 'automatic',
                'fuel_type' => 'gasoline',
                'engine_capacity_cc' => 2000,
                'fuel_consumption' => 6.2,
                'price_per_day' => 950000,
                'pickup_location' => 'Bình Thạnh, TP. Hồ Chí Minh',
                'description' => 'Sedan phong cách, trang bị tiện nghi.',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(
                ['license_plate' => $vehicle['license_plate']],
                [...$vehicle, 'status' => 1]
            );
        }
    }
}
