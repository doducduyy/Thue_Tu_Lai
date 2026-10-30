@extends('layouts.site')
@section('title', 'Thông tin cá nhân — Tự Lái Cuteo')
@section('content')
    <section class="customer-hero customer-hero-compact">
        <div><span class="eyebrow eyebrow-light">TÀI KHOẢN CỦA BẠN</span><h1>Thông tin cá nhân</h1><p>Giữ hồ sơ liên hệ luôn chính xác để nhận xe thuận tiện.</p></div>
        <a class="button button-light" href="{{ route('customer.home') }}"><i class="fa-solid fa-arrow-left"></i> Về tài khoản</a>
    </section>
    <div class="customer-shell customer-profile-shell">
        <section class="customer-panel">
            <header class="customer-panel-heading"><div><span class="eyebrow">HỒ SƠ</span><h2>Thông tin liên hệ</h2></div></header>
            <form action="{{ route('customer.profile.update') }}" method="POST" class="profile-form">@csrf @method('PUT')
                <div class="form-grid">
                    <div class="form-group"><label for="fullname">Họ và tên</label><input id="fullname" type="text" name="fullname" class="form-control" value="{{ old('fullname', Auth::user()->fullname) }}" autocomplete="name" required></div>
                    <div class="form-group"><label for="phone">Số điện thoại</label><input id="phone" type="tel" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}" autocomplete="tel" required></div>
                    <div class="form-group"><label for="email">Email</label><input id="email" type="email" class="form-control" value="{{ Auth::user()->email }}" disabled></div>
                    <div class="form-group"><label for="id_card">CCCD</label><input id="id_card" type="text" class="form-control" value="{{ Auth::user()->id_card }}" disabled></div>
                    <div class="form-group profile-wide"><label for="address">Địa chỉ</label><input id="address" type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address) }}" autocomplete="street-address"></div>
                </div>
                <div class="form-actions"><button class="button button-dark" type="submit">Lưu thay đổi</button></div>
            </form>
        </section>
        <section class="customer-panel">
            <header class="customer-panel-heading"><div><span class="eyebrow">BẢO MẬT</span><h2>Đổi mật khẩu</h2></div></header>
            <form action="{{ route('customer.password.update') }}" method="POST" class="profile-form">@csrf @method('PUT')
                <div class="form-grid">
                    <div class="form-group profile-wide"><label for="current_password">Mật khẩu hiện tại</label><input id="current_password" type="password" name="current_password" class="form-control" autocomplete="current-password" required></div>
                    <div class="form-group"><label for="new_password">Mật khẩu mới</label><input id="new_password" type="password" name="new_password" class="form-control" autocomplete="new-password" minlength="8" required></div>
                    <div class="form-group"><label for="new_password_confirmation">Nhập lại mật khẩu mới</label><input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control" autocomplete="new-password" minlength="8" required></div>
                </div>
                <div class="form-actions"><button class="button button-dark" type="submit">Cập nhật mật khẩu</button></div>
            </form>
        </section>
    </div>
@endsection
