@extends('layouts.master')
@section('title', 'Dịch vụ bổ sung theo xe')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">TIỆN ÍCH ĐỘI XE</span><h1>Dịch vụ bổ sung</h1><p>Cấu hình các dịch vụ có thể được đặt kèm từng phương tiện.</p></div><a class="button button-dark" href="{{ route('serviceDetails.create') }}"><i class="fa-solid fa-plus"></i> Gắn dịch vụ cho xe</a></header>
    @forelse($groupedServiceDetails as $vehicleName => $details)
        <section class="admin-table-panel service-vehicle-panel">
            <header class="service-vehicle-heading"><i class="fa-solid fa-car-side"></i><strong>{{ $vehicleName }}</strong><span>{{ $details->count() }} dịch vụ</span></header>
            <div class="table-scroll"><table class="data-table">
                <thead><tr><th>DỊCH VỤ</th><th>SỐ LƯỢNG</th><th>ĐƠN GIÁ</th><th>THAO TÁC</th></tr></thead>
                <tbody>@foreach($details as $detail)
                    <tr><td><strong>{{ $detail->service->name ?? 'Dịch vụ đã xóa' }}</strong></td><td>{{ number_format($detail->quantity, 2) }} {{ $detail->service->unit_name ?? '' }}</td><td>{{ number_format($detail->service->unit_price ?? 0, 0, ',', '.') }}đ</td><td><div class="table-actions">
                        <a aria-label="Sửa dịch vụ" href="{{ route('serviceDetails.edit', [$detail->vehicle_id, $detail->service_id]) }}"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('serviceDetails.destroy', [$detail->vehicle_id, $detail->service_id]) }}" method="POST" onsubmit="return confirm('Gỡ dịch vụ này khỏi xe?')">@csrf @method('DELETE')<button type="submit" aria-label="Gỡ dịch vụ"><i class="fa-solid fa-trash"></i></button></form>
                    </div></td></tr>
                @endforeach</tbody>
            </table></div>
        </section>
    @empty
        <section class="admin-table-panel table-empty">Chưa có dịch vụ bổ sung nào được gắn với xe.</section>
    @endforelse
@endsection
