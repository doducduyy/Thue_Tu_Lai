@extends('layouts.master')
@section('title', 'Thêm khách hàng')
@section('content')
    <div class="page-title">
        <span class="eyebrow">HỒ SƠ & TÀI KHOẢN</span><h1>Thêm khách hàng</h1>
    </div>

    <div class="form-container">
        <div class="form-subtitle">THÔNG TIN HỒ SƠ CƠ BẢN</div>

        <form method="post" action="{{ route('users.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Họ và tên:</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Nhập đầy đủ họ tên" value="{{ old('fullname') }}" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Ví dụ: 0912345678" value="{{ old('phone') }}" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="ban@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Số CCCD:</label>
                    <input type="text" name="id_card" class="form-control" placeholder="Nhập số căn cước" value="{{ old('id_card') }}" required>
                </div>

                <div class="form-group">
                    <label>Ngày tạo hồ sơ:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group form-wide">
                    <label>Địa chỉ:</label>
                    <input type="text" name="address" class="form-control" placeholder="Nhập địa chỉ liên hệ" value="{{ old('address') }}" required>
                </div>

                <div class="form-group">
                    <label>Vai trò hệ thống:</label>
                    <input type="text" class="form-control" value="Khách hàng" disabled>
                    <input type="hidden" name="role" value="0">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('users.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-save">Tạo hồ sơ</button>
            </div>
        </form>
    </div>
@endsection
