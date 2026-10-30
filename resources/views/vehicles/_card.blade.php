<article class="vehicle-card">
    <a class="vehicle-photo" href="{{ route('vehicles.show', $vehicle) }}">
        @if($vehicle->image_url)
            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" loading="lazy">
        @else
            <span class="vehicle-placeholder"><i class="fa-solid {{ $vehicle->category === 'motorcycle' ? 'fa-motorcycle' : 'fa-car-side' }}"></i></span>
        @endif
        <span class="photo-badge">{{ $vehicle->category === 'motorcycle' ? 'XE MÁY' : 'Ô TÔ' }}</span>
        @if($vehicle->status === 2)<span class="photo-status">Đang được đặt</span>@endif
    </a>
    <div class="vehicle-copy">
        <div class="vehicle-heading">
            <div><span class="vehicle-brand">{{ $vehicle->brand }}@if($vehicle->model_year) · {{ $vehicle->model_year }}@endif</span><h3><a href="{{ route('vehicles.show', $vehicle) }}">{{ $vehicle->name }}</a></h3></div>
            <span class="vehicle-rating"><i class="fa-solid fa-star"></i> 4.9</span>
        </div>
        <div class="vehicle-specs" aria-label="Thông số nhanh">
            <span><i class="fa-solid fa-user-group"></i>{{ $vehicle->seat_count }} chỗ</span>
            <span><i class="fa-solid fa-gears"></i>{{ $vehicle->transmission === 'automatic' ? 'Tự động' : 'Số sàn' }}</span>
            <span><i class="fa-solid fa-gas-pump"></i>{{ ['gasoline' => 'Xăng', 'diesel' => 'Dầu', 'electric' => 'Điện'][$vehicle->fuel_type] ?? $vehicle->fuel_type }}</span>
        </div>
        <div class="vehicle-card-bottom">
            <p class="daily-price"><strong>{{ number_format($vehicle->price_per_day) }}đ</strong><span>/ ngày</span></p>
            <a class="button button-dark" href="{{ route('vehicles.show', $vehicle) }}">Thuê ngay <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</article>
