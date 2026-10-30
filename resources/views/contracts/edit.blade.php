@extends('layouts.master')
@section('title', 'Cập nhật hợp đồng thuê xe')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">ĐIỀU PHỐI ĐẶT XE</span><h1>Cập nhật hợp đồng #{{ $contract->id }}</h1><p>Điều chỉnh lịch thuê, xe hoặc thông tin khách hàng.</p></div></header>
    <div class="form-container">
        <div class="form-subtitle">CHỈNH SỬA THÔNG TIN ĐẶT XE</div>
        @if ($errors->any())<div class="alert" role="alert">{{ $errors->first() }}</div>@endif
        <form method="post" action="{{ route('contracts.update', $contract) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Khách hàng:</label>
                    <select name="user_id" class="form-control" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $contract->user_id) == $user->id)>{{ $user->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Dòng xe:</label>
                    <select name="vehicle_id" class="form-control" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $contract->vehicle_id) == $vehicle->id)>
                                {{ $vehicle->name }} ({{ $vehicle->license_plate }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Địa điểm nhận xe:</label>
                    <input type="text" name="pickup_location" class="form-control" value="{{ old('pickup_location', $contract->pickup_location) }}" placeholder="Địa điểm giao xe">
                </div>
                <div class="form-group">
                    <label>Ngày và giờ nhận xe:</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="form-group">
                    <label>Ngày và giờ trả xe:</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="form-group">
                    <label>Tiền đặt cọc (VNĐ):</label>
                    <input type="number" name="deposit" class="form-control" min="0" value="{{ old('deposit', $contract->deposit) }}" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái hợp đồng:</label>
                    <select name="status" class="form-control" required>
                        <option value="1" @selected(old('status', $contract->status) == 1)>Đang hiệu lực</option>
                        <option value="0" @selected(old('status', $contract->status) == 0)>Đã kết thúc</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('contracts.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-update">Cập nhật hợp đồng</button>
            </div>
        </form>
    </div>
@endsection
