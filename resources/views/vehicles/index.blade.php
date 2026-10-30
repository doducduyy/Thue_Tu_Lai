@extends('layouts.site')
@section('title', 'Tìm xe tự lái')
@section('content')
    <section class="catalog-banner">
        <div><span class="eyebrow eyebrow-light">ĐỘI XE SẴN SÀNG LĂN BÁNH</span><h1>Tìm chuyến đi<br><em>theo cách của bạn.</em></h1><p>Chọn xe phù hợp, phần còn lại cứ để hành trình lo.</p></div>
        <div class="catalog-stat"><strong>{{ $vehicles->total() }}<small>+</small></strong><span>lựa chọn đồng hành</span></div>
    </section>
    <section class="catalog-shell">
        <form class="filter-panel" method="GET" action="{{ route('vehicles.index') }}">
            <div class="filter-title"><span><i class="fa-solid fa-sliders"></i> Bộ lọc</span><a href="{{ route('vehicles.index') }}">Đặt lại</a></div>
            <label class="filter-field">Tìm kiếm<input type="search" name="search" value="{{ request('search') }}" placeholder="Tên hoặc hãng xe"></label>
            <label class="filter-field">Địa điểm nhận<input type="text" name="location" value="{{ request('location') }}" placeholder="Quận, thành phố"></label>
            <label class="filter-field">Nhận xe<input type="datetime-local" name="pickup_at" value="{{ request('pickup_at') }}"></label>
            <label class="filter-field">Trả xe<input type="datetime-local" name="return_at" value="{{ request('return_at') }}"></label>
            <label class="filter-field">Hãng xe
                <select name="brand"><option value="">Tất cả hãng</option>@foreach($brands as $brand)<option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>@endforeach</select>
            </label>
            <label class="filter-field">Loại phương tiện
                <select name="category"><option value="">Tất cả loại xe</option><option value="car" @selected(request('category') === 'car')>Ô tô</option><option value="motorcycle" @selected(request('category') === 'motorcycle')>Xe máy</option></select>
            </label>
            <label class="filter-field">Dòng xe
                @php($segmentLabels = ['sedan' => 'Sedan', 'suv' => 'SUV', 'hatchback' => 'Hatchback', 'van' => 'MPV / Van', 'scooter' => 'Xe tay ga', 'motorcycle' => 'Xe số / côn tay', 'electric-motorcycle' => 'Xe máy điện'])
                <select name="segment"><option value="">Tất cả dòng xe</option>@foreach($segments as $segment)<option value="{{ $segment }}" @selected(request('segment') === $segment)>{{ $segmentLabels[$segment] ?? ucfirst($segment) }}</option>@endforeach</select>
            </label>
            <label class="filter-field">Giá tối đa / ngày
                <select name="price_max">
                    <option value="">Mọi mức giá</option>
                    <option value="500000" @selected(request('price_max') == 500000)>Dưới 500.000đ</option>
                    <option value="1000000" @selected(request('price_max') == 1000000)>Dưới 1.000.000đ</option>
                    <option value="2000000" @selected(request('price_max') == 2000000)>Dưới 2.000.000đ</option>
                </select>
            </label>
            <button type="submit" class="button button-dark filter-submit"><i class="fa-solid fa-magnifying-glass"></i> Xem xe phù hợp</button>
            <div class="filter-help"><i class="fa-regular fa-circle-question"></i><span>Chưa biết chọn xe nào?<br><a href="tel:19001234">Gọi tư vấn miễn phí</a></span></div>
        </form>

        <div class="catalog-results">
            <div class="catalog-toolbar"><div><span class="eyebrow">DANH SÁCH XE</span><h2>{{ $vehicles->total() }} xe phù hợp</h2></div><label>Sắp xếp
                <select onchange="const url=new URL(location);url.searchParams.set('sort',this.value);location=url">
                    <option value="recommended" @selected(request('sort','recommended') === 'recommended')>Đề xuất</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá thấp trước</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá cao trước</option>
                </select>
            </label></div>
            <div class="vehicle-grid">
                @forelse($vehicles as $vehicle)
                    @include('vehicles._card', ['vehicle' => $vehicle])
                @empty
                    <div class="empty-state"><i class="fa-solid fa-car-side"></i><h3>Chưa tìm thấy xe phù hợp</h3><p>Thử chọn thời gian khác hoặc bỏ bớt bộ lọc nhé.</p><a class="button button-dark" href="{{ route('vehicles.index') }}">Xem toàn bộ xe</a></div>
                @endforelse
            </div>
            <nav class="pagination-wrap" aria-label="Phân trang">{{ $vehicles->withQueryString()->links() }}</nav>
        </div>
    </section>
@endsection
