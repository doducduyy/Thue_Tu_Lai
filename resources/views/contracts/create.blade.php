@extends('layouts.master')
@section('title', 'Tạo hợp đồng thuê xe')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">ĐIỀU PHỐI ĐẶT XE</span><h1>Tạo hợp đồng thuê xe</h1><p>Chọn khách hàng, xe và khoảng thời gian nhận trả.</p></div></header>
    <div class="form-container">
        <div class="form-subtitle">THÔNG TIN ĐẶT XE</div>
        @if ($errors->any())<div class="alert" role="alert">{{ $errors->first() }}</div>@endif
        <form method="post" action="{{ route('contracts.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Khách hàng:</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->fullname }} - {{ $user->phone }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Dòng xe:</label>
                    <select name="vehicle_id" class="form-control" required>
                        <option value="">-- Chọn xe --</option>
                        @forelse($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                {{ $vehicle->name }} ({{ $vehicle->license_plate }}) — {{ number_format($vehicle->price_per_day) }}đ/ngày
                            </option>
                        @empty
                            <option value="" disabled>Hiện không có xe sẵn sàng</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label>Địa điểm nhận xe:</label>
                    <input type="text" name="pickup_location" class="form-control" value="{{ old('pickup_location') }}" placeholder="Ví dụ: Quận 1, TP. Hồ Chí Minh">
                </div>
                <div class="form-group">
                    <label>Ngày và giờ nhận xe:</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="form-group">
                    <label>Ngày và giờ trả xe:</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
                <div class="form-group">
                    <label>Tiền đặt cọc (VNĐ):</label>
                    <input type="number" name="deposit" class="form-control" min="0" value="{{ old('deposit', 0) }}" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái hợp đồng:</label>
                    <select name="status" class="form-control" required>
                        <option value="1" @selected(old('status', '1') == '1')>Đang hiệu lực</option>
                        <option value="0" @selected(old('status') == '0')>Đã kết thúc</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('contracts.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-save">Xác nhận đặt xe</button>
            </div>
        </form>
    </div>
@endsection
