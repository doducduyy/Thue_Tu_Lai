@extends('layouts.site')
@section('title', 'Hợp đồng của tôi — Tự Lái Cuteo')
@section('content')
    <section class="customer-hero customer-hero-compact">
        <div><span class="eyebrow eyebrow-light">HỒ SƠ CHUYẾN ĐI</span><h1>Hợp đồng thuê xe</h1><p>Thông tin xác nhận và thời gian của chuyến thuê.</p></div>
        <a class="button button-light" href="{{ route('customer.home') }}"><i class="fa-solid fa-arrow-left"></i> Về tài khoản</a>
    </section>
    <div class="customer-shell">
        @if($contract)
            <section class="customer-panel contract-sheet">
                <header class="contract-sheet-header"><div><span class="eyebrow">MÃ HỢP ĐỒNG #HD-{{ str_pad($contract->id, 5, '0', STR_PAD_LEFT) }}</span><h2>Thông tin thuê xe</h2></div><span class="status-chip status-{{ $contract->status == 1 ? 1 : 3 }}">{{ $contract->status == 1 ? 'Đang hiệu lực' : 'Đã kết thúc' }}</span></header>
                <div class="contract-vehicle">
                    <div class="customer-vehicle-photo">@if($contract->vehicle?->image_url)<img src="{{ $contract->vehicle->image_url }}" alt="{{ $contract->vehicle->name }}">@else<i class="fa-solid fa-car-side"></i>@endif</div>
                    <div><span class="eyebrow">PHƯƠNG TIỆN</span><h3>{{ $contract->vehicle->name ?? 'Xe đã xóa' }}</h3><p>{{ $contract->vehicle->license_plate ?? '' }}</p></div>
                </div>
                <dl class="contract-facts">
                    <div><dt>Khách hàng</dt><dd>{{ $customer->fullname }}</dd></div>
                    <div><dt>Địa điểm nhận xe</dt><dd>{{ $contract->pickup_location ?: ($contract->vehicle->pickup_location ?: 'Liên hệ để xác nhận') }}</dd></div>
                    <div><dt>Ngày, giờ nhận</dt><dd>{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y H:i') }}</dd></div>
                    <div><dt>Ngày, giờ trả</dt><dd>{{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y H:i') }}</dd></div>
                    <div><dt>Giá thuê</dt><dd>{{ number_format($contract->vehicle->price_per_day ?? 0, 0, ',', '.') }}đ / ngày</dd></div>
                    <div><dt>Tiền đặt cọc</dt><dd>{{ number_format($contract->deposit, 0, ',', '.') }}đ</dd></div>
                </dl>
            </section>
        @else
            <div class="customer-panel"><div class="empty-state"><i class="fa-solid fa-file-signature"></i><h3>Bạn chưa có hợp đồng thuê xe</h3><p>Chọn xe và gửi yêu cầu đặt xe để bắt đầu hành trình.</p><a class="button button-dark" href="{{ route('vehicles.index') }}">Khám phá xe</a></div></div>
        @endif
    </div>
@endsection
