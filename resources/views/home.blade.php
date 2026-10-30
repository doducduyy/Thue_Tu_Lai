@extends('layouts.site')
@section('title', 'Tự Lái Cuteo — Thuê xe, tự do khám phá')
@section('content')
    <section class="home-hero">
        <div class="hero-shade"></div>
        <div class="hero-content">
            <span class="eyebrow eyebrow-light"><i class="fa-solid fa-sparkles"></i> MỖI CHUYẾN ĐI, MỘT CÂU CHUYỆN</span>
            <h1>Chạm tay chọn xe.<br><em>Tự do lên đường.</em></h1>
            <p>Thuê xe máy, ô tô tự lái với mức giá rõ ràng và nhận xe ngay tại địa điểm bạn chọn.</p>
            <form class="quick-search" action="{{ route('vehicles.index') }}" method="GET">
                <label class="search-field">
                    <span><i class="fa-solid fa-location-dot"></i> ĐỊA ĐIỂM NHẬN XE</span>
                    <input type="text" name="location" value="{{ request('location') }}" placeholder="">
                </label>
                <label class="search-field">
                    <span><i class="fa-regular fa-calendar"></i> NGÀY & GIỜ NHẬN</span>
                    <input type="datetime-local" name="pickup_at" value="{{ request('pickup_at') }}">
                </label>
                <label class="search-field">
                    <span><i class="fa-regular fa-calendar-check"></i> NGÀY & GIỜ TRẢ</span>
                    <input type="datetime-local" name="return_at" value="{{ request('return_at') }}">
                </label>
                <label class="search-field search-type">
                    <span><i class="fa-solid fa-car-side"></i> LOẠI XE</span>
                    <select name="category"><option value="">Tất cả xe</option><option value="car" @selected(request('category') === 'car')>Ô tô</option><option value="motorcycle" @selected(request('category') === 'motorcycle')>Xe máy</option></select>
                </label>
                <button class="button button-accent search-submit" type="submit">Tìm xe <i class="fa-solid fa-arrow-right"></i></button>
            </form>
            <div class="hero-trust"><span><i class="fa-solid fa-circle-check"></i> Giá thuê minh bạch</span><span><i class="fa-solid fa-circle-check"></i> Hỗ trợ tận tâm</span><span><i class="fa-solid fa-circle-check"></i> Nhận xe linh hoạt</span></div>
        </div>
        <div class="hero-index"><strong>01</strong><span>—</span> HÀNH TRÌNH CỦA BẠN</div>
    </section>

    <section class="content-section featured-section">
        <div class="section-topline"><span class="eyebrow">CHỌN CHIẾC XE HỢP GU</span><a class="text-link" href="{{ route('vehicles.index') }}">Xem tất cả xe <i class="fa-solid fa-arrow-right"></i></a></div>
        <div class="section-heading-row"><div><h2>Những lựa chọn <em>được yêu thích</em></h2></div><a class="text-link" href="{{ route('vehicles.index') }}">Tìm xe của bạn <i class="fa-solid fa-arrow-right"></i></a></div>
        <div class="vehicle-grid vehicle-grid-home">
            @forelse($vehicles as $vehicle)
                @include('vehicles._card', ['vehicle' => $vehicle])
            @empty
                <div class="empty-state">Chưa có xe phù hợp. Hãy thử thay đổi ngày thuê hoặc bộ lọc.</div>
            @endforelse
        </div>
    </section>

    <section class="steps-section" id="how-it-works">
        <div class="steps-intro"><span class="eyebrow eyebrow-light">THẬT NHANH. THẬT DỄ.</span><h2>Ba bước để<br><em>bắt đầu hành trình</em></h2><p>Chọn xe yêu thích, xác nhận đặt cọc và sẵn sàng nhận xe tận nơi.</p><a class="button button-light" href="{{ route('vehicles.index') }}">Chọn xe ngay <i class="fa-solid fa-arrow-right"></i></a></div>
        <div class="steps-list">
            <article class="step-item"><span class="step-number">01</span><span class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></span><div><h3>Chọn xe</h3><p>Tìm chiếc xe phù hợp theo điểm nhận, thời gian và ngân sách.</p></div><i class="fa-solid fa-arrow-up-right-from-square step-arrow"></i></article>
            <article class="step-item"><span class="step-number">02</span><span class="step-icon"><i class="fa-regular fa-credit-card"></i></span><div><h3>Đặt cọc</h3><p>Gửi yêu cầu thuê và xác nhận thông tin đặt xe an toàn.</p></div><i class="fa-solid fa-arrow-up-right-from-square step-arrow"></i></article>
            <article class="step-item"><span class="step-number">03</span><span class="step-icon"><i class="fa-solid fa-key"></i></span><div><h3>Nhận xe tận nơi</h3><p>Nhận xe đúng giờ tại địa điểm thuận tiện với bạn.</p></div><i class="fa-solid fa-arrow-up-right-from-square step-arrow"></i></article>
        </div>
    </section>

    <section class="closing-banner"><div><span class="eyebrow">SẴN SÀNG LÊN ĐƯỜNG?</span><h2>Chuyến đi tiếp theo<br><em>bắt đầu từ đây.</em></h2></div><a class="button button-dark" href="{{ route('vehicles.index') }}">Khám phá đội xe <i class="fa-solid fa-arrow-right"></i></a></section>
@endsection
