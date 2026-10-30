@extends('layouts.site')
@section('title', 'Tự Lái Cuteo — Thuê xe máy và ô tô tự lái')
@section('content')
    <section class="welcome-hero">
        <span class="eyebrow eyebrow-light">TỰ DO TRÊN MỌI CUNG ĐƯỜNG</span>
        <h1>Chiếc xe phù hợp.<br><em>Hành trình của riêng bạn.</em></h1>
        <p>Chọn xe máy hoặc ô tô tự lái, đặt lịch linh hoạt và nhận xe tại điểm hẹn.</p>
        <div><a class="button button-accent" href="{{ route('vehicles.index') }}">Khám phá đội xe <i class="fa-solid fa-arrow-right"></i></a><a class="button button-light" href="{{ route('register') }}">Tạo tài khoản</a></div>
    </section>
@endsection
