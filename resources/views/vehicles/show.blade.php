@extends('layouts.site')
@section('title', $vehicle->name . ' — Thuê xe tự lái')
@section('content')
    @php
        $photos = collect($vehicle->gallery ?? [])->prepend($vehicle->image)->filter()->unique()->values();
        $fuelLabels = ['gasoline' => 'Xăng', 'diesel' => 'Dầu', 'electric' => 'Điện'];
        $transmissionLabel = $vehicle->transmission === 'automatic' ? 'Tự động' : 'Số sàn';
    @endphp
    <section class="vehicle-detail">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('home') }}">Trang chủ</a><i class="fa-solid fa-chevron-right"></i><a href="{{ route('vehicles.index') }}">Thuê xe</a><i class="fa-solid fa-chevron-right"></i><span>{{ $vehicle->name }}</span></nav>
        <div class="detail-title"><div><span class="eyebrow">{{ $vehicle->brand }} · {{ $vehicle->category === 'motorcycle' ? 'XE MÁY' : 'Ô TÔ' }}</span><h1>{{ $vehicle->name }}</h1><p><i class="fa-solid fa-location-dot"></i> {{ $vehicle->pickup_location ?: 'Địa điểm nhận xe xác nhận sau' }} <span class="title-divider">·</span> <i class="fa-solid fa-star rating-star"></i> 4.9 (32 đánh giá)</p></div><span class="availability-pill"><i class="fa-solid fa-circle"></i> {{ [1 => 'Sẵn sàng đặt xe', 2 => 'Đang có lịch thuê', 3 => 'Đang bảo dưỡng'][$vehicle->status] ?? 'Đang cập nhật' }}</span></div>
        <div class="detail-layout">
            <div class="detail-main">
                <section class="gallery-section" aria-label="Thư viện ảnh xe">
                    <div class="gallery-main">
                        @if($photos->isNotEmpty())
                            <img id="gallery-main-image" src="{{ $vehicle->image_url ?: $photos->first() }}" alt="{{ $vehicle->name }} — ảnh xe">
                        @else
                            <div class="vehicle-placeholder gallery-placeholder"><i class="fa-solid {{ $vehicle->category === 'motorcycle' ? 'fa-motorcycle' : 'fa-car-side' }}"></i></div>
                        @endif
                        <span class="photo-badge">ẢNH THỰC TẾ</span>
                    </div>
                    @if($photos->count() > 1)
                        <div class="gallery-thumbs">
                            @foreach($photos as $photo)
                                <button type="button" class="gallery-thumb" data-image="{{ filter_var($photo, FILTER_VALIDATE_URL) ? $photo : \Illuminate\Support\Facades\Storage::url($photo) }}" aria-label="Xem ảnh xe góc {{ $loop->iteration }}">
                                    <img src="{{ filter_var($photo, FILTER_VALIDATE_URL) ? $photo : \Illuminate\Support\Facades\Storage::url($photo) }}" alt="Ảnh {{ $loop->iteration }} của {{ $vehicle->name }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </section>
                <section class="detail-panel">
                    <span class="eyebrow">THÔNG SỐ CHI TIẾT</span><h2>Sẵn sàng cho mọi hành trình</h2>
                    <div class="detail-spec-grid">
                        <div><i class="fa-solid fa-users"></i><span>Số chỗ ngồi</span><strong>{{ $vehicle->seat_count }} chỗ</strong></div>
                        <div><i class="fa-solid fa-gears"></i><span>Hộp số</span><strong>{{ $transmissionLabel }}</strong></div>
                        <div><i class="fa-solid fa-gas-pump"></i><span>Nhiên liệu</span><strong>{{ $fuelLabels[$vehicle->fuel_type] ?? $vehicle->fuel_type }}</strong></div>
                        <div><i class="fa-regular fa-calendar"></i><span>Năm sản xuất</span><strong>{{ $vehicle->model_year ?: 'Đang cập nhật' }}</strong></div>
                        <div><i class="fa-solid fa-gauge-high"></i><span>Dung tích xi-lanh</span><strong>{{ $vehicle->engine_capacity_cc ? number_format($vehicle->engine_capacity_cc) . ' cc' : 'Đang cập nhật' }}</strong></div>
                        <div><i class="fa-solid fa-leaf"></i><span>Tiêu hao nhiên liệu</span><strong>{{ $vehicle->fuel_consumption ? $vehicle->fuel_consumption . ' L/100km' : 'Đang cập nhật' }}</strong></div>
                    </div>
                </section>
                @if($vehicle->description)
                    <section class="detail-panel vehicle-description"><span class="eyebrow">GIỚI THIỆU XE</span><p>{{ $vehicle->description }}</p></section>
                @endif
            </div>

            <aside class="booking-panel">
                <div class="booking-price"><span>Giá thuê từ</span><strong>{{ number_format($vehicle->price_per_day) }}đ</strong><small>/ ngày</small></div>
                <div class="booking-rating"><i class="fa-solid fa-star"></i> 4.9 <span>· 32 đánh giá</span></div>
                @auth
                    <form action="{{ route('bookings.store') }}" method="POST" class="booking-form">
                        @csrf
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <label class="booking-label">Ngày & giờ nhận xe<input type="datetime-local" name="start_date" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('start_date', request('pickup_at')) }}" required></label>
                        <label class="booking-label">Ngày & giờ trả xe<input type="datetime-local" name="end_date" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('end_date', request('return_at')) }}" required></label>
                        <label class="booking-label">Địa điểm nhận xe<input type="text" name="pickup_location" value="{{ old('pickup_location', $vehicle->pickup_location) }}" placeholder="Nhập địa điểm nhận xe" required></label>
                        <fieldset class="service-options"><legend>Dịch vụ thêm</legend>
                            @foreach($services as $service)
                                <label><input type="checkbox" name="service_ids[]" value="{{ $service->id }}" data-price="{{ $service->unit_price }}" data-unit="{{ $service->unit_name }}" @checked(in_array($service->id, old('service_ids', [])))><span>{{ $service->name }}</span><small>+{{ number_format($service->unit_price) }}đ / {{ $service->unit_name ?: 'lượt' }}</small></label>
                            @endforeach
                        </fieldset>
                        <div class="estimate-box"><div><span>Số ngày thuê</span><strong id="rental-days">—</strong></div><div><span>Tạm tính</span><strong id="rental-estimate">—</strong></div></div>
                        <button class="button button-accent booking-submit" type="submit" @disabled((int)$vehicle->status === 3)>Gửi yêu cầu thuê xe <i class="fa-solid fa-arrow-right"></i></button>
                        <p class="booking-note"><i class="fa-solid fa-shield-halved"></i> Chưa trừ tiền. Nhân viên sẽ xác nhận lịch và tiền cọc.</p>
                    </form>
                @else
                    <div class="booking-login"><p>Đăng nhập để chọn lịch và gửi yêu cầu thuê xe.</p><a class="button button-accent booking-submit" href="{{ route('admin.login') }}">Đăng nhập để thuê <i class="fa-solid fa-arrow-right"></i></a><a href="{{ route('register') }}">Chưa có tài khoản? Đăng ký</a></div>
                @endauth
                <div class="booking-contact"><i class="fa-solid fa-headset"></i><span>Cần hỗ trợ?<br><a href="tel:19001234">1900 1234</a></span></div>
            </aside>
        </div>
    </section>
    <script>
        const startField = document.querySelector('[name="start_date"]');
        const endField = document.querySelector('[name="end_date"]');
        const dayLabel = document.getElementById('rental-days');
        const totalLabel = document.getElementById('rental-estimate');
        const dailyPrice = {{ (int) $vehicle->price_per_day }};
        function updateEstimate() {
            if (!dayLabel || !totalLabel) return;
            const hasDates = startField?.value && endField?.value;
            const minutes = hasDates ? (new Date(endField.value) - new Date(startField.value)) / 60000 : 0;
            const days = minutes > 0 ? Math.max(1, Math.ceil(minutes / 1440)) : 0;
            const extras = [...document.querySelectorAll('.service-options input:checked')].reduce((sum, input) => {
                const perDay = ['ngày', 'day', 'ngày thuê'].includes((input.dataset.unit || '').toLocaleLowerCase('vi'));
                return sum + Number(input.dataset.price || 0) * (perDay ? days : 1);
            }, 0);
            dayLabel.textContent = days > 0 ? `${days} ngày` : '—';
            totalLabel.textContent = days > 0 ? `${(days * dailyPrice + extras).toLocaleString('vi-VN')}đ` : '—';
        }
        startField?.addEventListener('change', updateEstimate);
        endField?.addEventListener('change', updateEstimate);
        document.querySelectorAll('.service-options input').forEach(input => input.addEventListener('change', updateEstimate));
        updateEstimate();
        document.querySelectorAll('.gallery-thumb').forEach(button => button.addEventListener('click', () => {
            document.getElementById('gallery-main-image').src = button.dataset.image;
        }));
    </script>
@endsection
