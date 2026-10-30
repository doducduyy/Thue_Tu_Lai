<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleRentalInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_booking_keeps_pickup_location_and_selected_service_price(): void
    {
        $customer = User::create([
            'fullname' => 'Khách thuê thử',
            'email' => 'booking-customer@example.com',
            'password' => 'password',
            'phone' => '0900000011',
            'id_card' => '000000000011',
            'address' => 'TP. Hồ Chí Minh',
            'role' => 0,
            'status' => 1,
        ]);
        $admin = User::create([
            'fullname' => 'Quản lý thử',
            'email' => 'booking-admin@example.com',
            'password' => 'password',
            'phone' => '0900000012',
            'id_card' => '000000000012',
            'address' => 'TP. Hồ Chí Minh',
            'role' => 1,
            'status' => 1,
        ]);
        $vehicle = Vehicle::create([
            'name' => 'Toyota Vios 2024',
            'license_plate' => '51A-654.32',
            'seat_count' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'gasoline',
            'price_per_day' => 700000,
            'status' => 1,
        ]);
        $service = Service::create([
            'name' => 'Bảo hiểm thân vỏ',
            'unit_price' => 150000,
            'unit_name' => 'lượt',
        ]);
        $start = now()->addDays(2)->setTime(10, 0);
        $end = $start->copy()->addDays(2);

        $this->actingAs($customer)
            ->post(route('bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'start_date' => $start->format('Y-m-d\TH:i'),
                'end_date' => $end->format('Y-m-d\TH:i'),
                'pickup_location' => 'Sân bay Tân Sơn Nhất',
                'service_ids' => [$service->id],
            ])
            ->assertRedirect(route('customer.contract'));

        $contract = Contract::firstOrFail();
        $this->assertSame('Sân bay Tân Sơn Nhất', $contract->pickup_location);
        $this->assertSame(150000.0, (float) $contract->services()->first()->pivot->unit_price);

        $service->update(['unit_price' => 300000]);
        $this->actingAs($admin)
            ->post(route('invoices.store'), [
                'contract_id' => $contract->id,
                'invoice_date' => now()->toDateString(),
                'status' => 0,
            ])
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::where('contract_id', $contract->id)->firstOrFail();
        $this->assertSame(150000.0, (float) $invoice->service_fee);
        $this->assertSame(1550000.0, (float) $invoice->total_amount);
    }

    public function test_invoice_total_uses_rental_days_and_vehicle_services(): void
    {
        $customer = User::create([
            'fullname' => 'Khách thuê thử',
            'email' => 'customer@example.com',
            'password' => 'password',
            'phone' => '0900000001',
            'id_card' => '000000000001',
            'address' => 'TP. Hồ Chí Minh',
            'role' => 0,
            'status' => '1',
        ]);
        $admin = User::create([
            'fullname' => 'Quản lý thử',
            'email' => 'admin@example.com',
            'password' => 'password',
            'phone' => '0900000002',
            'id_card' => '000000000002',
            'address' => 'TP. Hồ Chí Minh',
            'role' => 1,
            'status' => '1',
        ]);
        $vehicle = Vehicle::create([
            'name' => 'Toyota Vios 2024',
            'license_plate' => '51A-123.45',
            'seat_count' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'gasoline',
            'price_per_day' => 700000,
            'pickup_location' => 'Quận 1',
            'status' => 2,
        ]);
        $contract = Contract::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-04',
            'deposit' => 1000000,
            'status' => 1,
        ]);

        $dailyInsurance = Service::create([
            'name' => 'Bảo hiểm thân vỏ',
            'unit_price' => 150000,
            'unit_name' => 'ngày',
        ]);
        $delivery = Service::create([
            'name' => 'Phí giao xe tận nhà',
            'unit_price' => 100000,
            'unit_name' => 'lượt',
        ]);
        $helmet = Service::create([
            'name' => 'Thuê thêm mũ bảo hiểm',
            'unit_price' => 20000,
            'unit_name' => 'chiếc',
        ]);

        foreach ([
            [$dailyInsurance->id, 1],
            [$delivery->id, 1],
            [$helmet->id, 2],
        ] as [$serviceId, $quantity]) {
            ServiceDetail::create([
                'vehicle_id' => $vehicle->id,
                'service_id' => $serviceId,
                'quantity' => $quantity,
            ]);
        }

        $this->actingAs($admin)
            ->post(route('invoices.store'), [
                'contract_id' => $contract->id,
                'invoice_date' => '2026-10-01',
                'status' => 0,
            ])
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::where('contract_id', $contract->id)->firstOrFail();
        $this->assertSame(3, $invoice->rental_days);
        $this->assertSame(2100000.0, (float) $invoice->vehicle_amount);
        $this->assertSame(590000.0, (float) $invoice->service_fee);
        $this->assertSame(2690000.0, (float) $invoice->total_amount);
        $this->assertDatabaseCount('invoice_details', 3);
    }
}
