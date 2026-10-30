<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            ['category' => 'car', 'segment' => 'sedan'],
            ['category' => 'car', 'segment' => 'suv'],
            ['category' => 'motorcycle', 'segment' => 'scooter'],
            ['category' => 'motorcycle', 'segment' => 'motorcycle'],
        ]);

        return [
            'name' => fake()->randomElement(['Toyota Vios 2024', 'Honda City 2023', 'Honda Vision 2024']),
            'brand' => fake()->randomElement(['Toyota', 'Mazda', 'Honda', 'VinFast']),
            ...$type,
            'model_year' => fake()->numberBetween(2020, 2025),
            'license_plate' => strtoupper(fake()->unique()->bothify('##A-###.##')),
            'image' => null,
            'seat_count' => fake()->randomElement([2, 4, 5, 7]),
            'transmission' => fake()->randomElement(['automatic', 'manual']),
            'fuel_type' => fake()->randomElement(['gasoline', 'diesel', 'electric']),
            'engine_capacity_cc' => fake()->numberBetween(110, 2500),
            'fuel_consumption' => fake()->randomFloat(1, 2, 10),
            'price_per_day' => fake()->numberBetween(200000, 1500000),
            'pickup_location' => fake()->city(),
            'status' => 1,
            'description' => fake()->sentence(),
        ];
    }
}
