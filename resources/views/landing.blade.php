@extends('layouts.site')
@section('title', 'Tự Lái Cuteo — Tìm xe cho chuyến đi tiếp theo')
@section('content')
    <section class="welcome-hero">
        <span class="eyebrow eyebrow-light">ĐỘI XE SẴN SÀNG LĂN BÁNH</span>
        <h1>Tìm chuyến đi<br><em>theo cách của bạn.</em></h1>
        <p>Khám phá xe cho mọi lịch trình — từ một vòng phố bằng xe máy đến chuyến đi xa cùng gia đình.</p>
        <div><a class="button button-accent" href="{{ route('vehicles.index') }}">Tìm xe ngay <i class="fa-solid fa-arrow-right"></i></a><a class="button button-light" href="{{ route('home') }}">Trang chủ</a></div>
    </section>
@endsection
