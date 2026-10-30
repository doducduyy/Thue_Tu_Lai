@extends('layouts.site')
@section('title', 'Tài khoản của tôi — Tự Lái Cuteo')
@section('content')
    <section class="customer-hero">
        <div><span class="eyebrow eyebrow-light">KHÔNG GIAN CỦA BẠN</span><h1>Xin chào, {{ $customer->fullname ?? 'bạn' }}.</h1><p>Theo dõi đặt xe, hợp đồng và hóa đơn tại một nơi.</p></div>
        <a class="button button-accent" href="{{ route('vehicles.index') }}">Khám phá đội xe <i class="fa-solid fa-arrow-right"></i></a>
    </section>
    <div class="customer-shell">
        <section class="customer-panel">
            <header class="customer-panel-heading"><div><span class="eyebrow">ĐẶT XE CỦA TÔI</span><h2>Chuyến đi hiện tại</h2></div><a class="text-link" href="{{ route('customer.contract') }}">Hợp đồng <i class="fa-solid fa-arrow-right"></i></a></header>
            @if($contract)
                <article class="customer-booking">
                    <div class="customer-vehicle-photo">
                        @if($contract->vehicle?->image_url)<img src="{{ $contract->vehicle->image_url }}" alt="{{ $contract->vehicle->name }}">@else<i class="fa-solid fa-car-side"></i>@endif
                    </div>
                    <div class="customer-booking-copy">
                        <span class="status-chip status-1">Đang hiệu lực</span>
                        <h3>{{ $contract->vehicle->name ?? 'Xe đã xóa' }}</h3>
                        <p>{{ $contract->vehicle->license_plate ?? '' }} <span>·</span> {{ $contract->pickup_location ?: ($contract->vehicle->pickup_location ?: 'Địa điểm nhận xe xác nhận sau') }}</p>
                        <div class="customer-dates"><span><small>NHẬN XE</small>{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y H:i') }}</span><i class="fa-solid fa-arrow-right"></i><span><small>TRẢ XE</small>{{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y H:i') }}</span></div>
                    </div>
                    <strong class="customer-price">{{ number_format($contract->vehicle->price_per_day ?? 0, 0, ',', '.') }}đ<small>/ngày</small></strong>
                </article>
            @else
                <div class="empty-state"><i class="fa-solid fa-key"></i><h3>Chưa có chuyến đi nào</h3><p>Chọn xe yêu thích và gửi yêu cầu thuê trong vài bước.</p><a class="button button-dark" href="{{ route('vehicles.index') }}">Tìm xe ngay</a></div>
            @endif
        </section>

        <section class="customer-panel">
            <header class="customer-panel-heading"><div><span class="eyebrow">THANH TOÁN</span><h2>Hóa đơn gần đây</h2></div><a class="text-link" href="{{ route('customer.invoices') }}">Tất cả hóa đơn <i class="fa-solid fa-arrow-right"></i></a></header>
            @if($invoices->isNotEmpty())
                <div class="table-scroll"><table class="customer-table">
                    <thead><tr><th>NGÀY LẬP</th><th>XE THUÊ</th><th>THỜI GIAN</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th></tr></thead>
                    <tbody>@foreach($invoices as $invoice)
                        <tr><td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td><td>{{ $invoice->vehicle->name ?? 'Xe đã xóa' }}</td><td>{{ $invoice->rental_days }} ngày</td><td><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}đ</strong></td><td><span class="status-chip status-{{ $invoice->status == 1 ? 1 : 2 }}">{{ $invoice->status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></td></tr>
                    @endforeach</tbody>
                </table></div>
            @else
                <p class="customer-empty">Hóa đơn sẽ xuất hiện tại đây sau khi hoàn tất chuyến thuê.</p>
            @endif
        </section>
        <nav class="customer-shortcuts" aria-label="Tài khoản">
            <a href="{{ route('customer.profile') }}"><i class="fa-regular fa-user"></i><span>Thông tin cá nhân<small>Cập nhật hồ sơ</small></span><i class="fa-solid fa-chevron-right"></i></a>
            <a href="{{ route('customer.statistics') }}"><i class="fa-solid fa-clock-rotate-left"></i><span>Lịch sử thuê xe<small>Xem các hóa đơn</small></span><i class="fa-solid fa-chevron-right"></i></a>
        </nav>
    </div>
@endsection
