<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Bảo hiểm thân vỏ', 'unit_price' => 150000, 'unit_name' => 'lượt'],
            ['name' => 'Phí giao xe tận nhà', 'unit_price' => 100000, 'unit_name' => 'lượt'],
            ['name' => 'Thuê thêm mũ bảo hiểm', 'unit_price' => 20000, 'unit_name' => 'chiếc'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['name' => $service['name']], $service);
        }
    }
}
