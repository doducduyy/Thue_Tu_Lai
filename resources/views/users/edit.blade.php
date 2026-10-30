@extends('layouts.master')

@section('title', 'Cập nhật hồ sơ khách hàng')

@section('content')
    <header class="page-heading"><div><span class="eyebrow">HỒ SƠ & TÀI KHOẢN</span><h1>Cập nhật khách hàng</h1><p>{{ $user->fullname }}</p></div></header>

    <div class="form-container">
        <div class="form-subtitle">CHỈNH SỬA THÔNG TIN HỒ SƠ CỦA KHÁCH </div>

        <form method="post" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Họ tên khách hàng: </label>
                    <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $user->fullname) }}" required>
                </div>

                <div class="form-group">
                    <label>Số điện thoại (SĐT): </label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                </div>

                <div class="form-group">
                    <label>Số CMND / CCCD: </label>
                    <input type="text" name="id_card" class="form-control" value="{{ old('id_card', $user->id_card) }}" required>
                </div>

                <div class="form-group">
                    <label>Địa chỉ thường trú (Địa chỉ): </label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required>
                </div>

                <div class="form-group">
                    <label>Vai trò hệ thống: </label>
                    <input type="text" class="form-control" value="Khách thuê" disabled>
                    <input type="hidden" name="role" value="0">
                </div>

                <div class="form-group">
                    <label for="status">Trạng thái tài khoản</label>
                    <select id="status" name="status" class="form-control">
                        <option value="1" @selected(old('status', $user->status) == 1)>Đang hoạt động</option>
                        <option value="0" @selected(old('status', $user->status) == 0)>Khóa tài khoản</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('users.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-update">Cập nhật hồ sơ</button>
            </div>
        </form>
    </div>
@endsection
