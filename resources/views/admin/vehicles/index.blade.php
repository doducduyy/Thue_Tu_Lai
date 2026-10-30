@extends('layouts.master')
@section('title', 'Quản lý đội xe')
@section('content')
    <header class="page-heading">
        <div><span class="eyebrow">QUẢN LÝ ĐỘI XE</span><h1>Danh sách xe</h1><p>Theo dõi tình trạng, thông tin và lịch khai thác từng phương tiện.</p></div>
        <a class="button button-dark" href="{{ route('admin.vehicles.create') }}"><i class="fa-solid fa-plus"></i> Thêm xe mới</a>
    </header>
    @if (session('error'))<div class="flash-message flash-error" role="alert">{{ session('error') }}</div>@endif
    <section class="admin-stat-row">
        <article class="admin-stat"><span>Tổng phương tiện</span><strong>{{ $totalVehicles }}</strong><i class="fa-solid fa-car-side"></i></article>
        <article class="admin-stat"><span>Sẵn sàng</span><strong>{{ $statusCounts[1] ?? 0 }}</strong><i class="fa-solid fa-circle-check"></i></article>
        <article class="admin-stat"><span>Đang cho thuê</span><strong>{{ $statusCounts[2] ?? 0 }}</strong><i class="fa-solid fa-key"></i></article>
        <article class="admin-stat"><span>Đang bảo dưỡng</span><strong>{{ $statusCounts[3] ?? 0 }}</strong><i class="fa-solid fa-screwdriver-wrench"></i></article>
    </section>
    <section class="admin-table-panel">
        <form class="table-toolbar" method="GET" action="{{ route('admin.vehicles.index') }}">
            <label class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Tìm tên xe hoặc biển số"></label>
            <select name="status" aria-label="Lọc theo trạng thái">
                <option value="">Mọi trạng thái</option>
                <option value="1" @selected(request('status') === '1')>Sẵn sàng</option>
                <option value="2" @selected(request('status') === '2')>Đang cho thuê</option>
                <option value="3" @selected(request('status') === '3')>Đang bảo dưỡng</option>
            </select>
            <button type="submit" class="button button-outline">Lọc danh sách</button>
        </form>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>PHƯƠNG TIỆN</th><th>PHÂN KHÚC</th><th>THÔNG SỐ</th><th>GIÁ / NGÀY</th><th>HỢP ĐỒNG</th><th>TÌNH TRẠNG</th><th>THAO TÁC</th></tr></thead>
                <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td><div class="table-vehicle">@if($vehicle->image_url)<img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}">@else<span class="table-vehicle-icon"><i class="fa-solid {{ $vehicle->category === 'motorcycle' ? 'fa-motorcycle' : 'fa-car-side' }}"></i></span>@endif<div><strong>{{ $vehicle->name }}</strong><small>{{ $vehicle->license_plate }} · {{ $vehicle->brand }}</small></div></div></td>
                        <td>{{ $vehicle->category === 'motorcycle' ? 'Xe máy' : 'Ô tô' }} · {{ ['sedan' => 'Sedan', 'suv' => 'SUV', 'hatchback' => 'Hatchback', 'van' => 'MPV / Van', 'scooter' => 'Xe tay ga', 'motorcycle' => 'Xe số / côn tay', 'electric-motorcycle' => 'Xe máy điện'][$vehicle->segment] ?? 'Chưa phân loại' }}</td>
                        <td>{{ $vehicle->seat_count }} chỗ · {{ $vehicle->transmission === 'automatic' ? 'Tự động' : 'Số sàn' }}</td>
                        <td><strong>{{ number_format($vehicle->price_per_day) }}đ</strong></td>
                        <td>{{ $vehicle->contracts_count }}</td>
                        <td><span class="status-chip status-{{ $vehicle->status }}">{{ [1 => 'Sẵn sàng', 2 => 'Đang cho thuê', 3 => 'Đang bảo dưỡng'][$vehicle->status] ?? 'Không rõ' }}</span></td>
                        <td><div class="table-actions"><a href="{{ route('vehicles.show', $vehicle) }}" aria-label="Xem xe"><i class="fa-regular fa-eye"></i></a><a href="{{ route('admin.vehicles.edit', $vehicle) }}" aria-label="Sửa xe"><i class="fa-regular fa-pen-to-square"></i></a><form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Xóa xe {{ $vehicle->name }}?')">@csrf @method('DELETE')<button aria-label="Xóa xe" type="submit"><i class="fa-regular fa-trash-can"></i></button></form></div></td>
                    </tr>
                @empty
                    <tr><td class="table-empty" colspan="7">Chưa có xe phù hợp. Hãy thêm phương tiện đầu tiên.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <footer class="table-footer"><span>Hiển thị {{ $vehicles->firstItem() ?? 0 }}–{{ $vehicles->lastItem() ?? 0 }} trong {{ $vehicles->total() }} xe</span>{{ $vehicles->withQueryString()->links() }}</footer>
    </section>
@endsection
