@extends('layouts.master')
@section('title', 'Cập nhật xe')
@section('content')
    <div class="page-title">
        Quản Lý Xe / <strong>Chỉnh sửa {{ $vehicle->name }}</strong>
    </div>

    <div class="form-container">
        <div class="form-subtitle">CẬP NHẬT THÔNG TIN XE</div>
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Tên xe:</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $vehicle->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Hãng xe:</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" required>
                </div>
                <div class="form-group">
                    <label>Loại phương tiện:</label>
                    <select name="category" class="form-control" required>
                        <option value="car" @selected(old('category', $vehicle->category) === 'car')>Ô tô</option>
                        <option value="motorcycle" @selected(old('category', $vehicle->category) === 'motorcycle')>Xe máy</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dòng xe:</label>
                    <select name="segment" class="form-control" required>
                        <option value="sedan" @selected(old('segment', $vehicle->segment) === 'sedan')>Sedan</option>
                        <option value="suv" @selected(old('segment', $vehicle->segment) === 'suv')>SUV</option>
                        <option value="hatchback" @selected(old('segment', $vehicle->segment) === 'hatchback')>Hatchback</option>
                        <option value="van" @selected(old('segment', $vehicle->segment) === 'van')>MPV / Van</option>
                        <option value="scooter" @selected(old('segment', $vehicle->segment) === 'scooter')>Xe tay ga</option>
                        <option value="motorcycle" @selected(old('segment', $vehicle->segment) === 'motorcycle')>Xe số / côn tay</option>
                        <option value="electric-motorcycle" @selected(old('segment', $vehicle->segment) === 'electric-motorcycle')>Xe máy điện</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Năm sản xuất:</label>
                    <input type="number" name="model_year" class="form-control" min="1990" max="2100" value="{{ old('model_year', $vehicle->model_year) }}">
                </div>
                <div class="form-group">
                    <label>Biển số:</label>
                    <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate', $vehicle->license_plate) }}" required>
                </div>
                <div class="form-group">
                    <label>Số chỗ ngồi:</label>
                    <input type="number" name="seat_count" class="form-control" min="1" value="{{ old('seat_count', $vehicle->seat_count) }}" required>
                </div>
                <div class="form-group">
                    <label>Hộp số:</label>
                    <select name="transmission" class="form-control" required>
                        <option value="automatic" @selected(old('transmission', $vehicle->transmission) === 'automatic')>Tự động</option>
                        <option value="manual" @selected(old('transmission', $vehicle->transmission) === 'manual')>Số sàn</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nhiên liệu:</label>
                    <select name="fuel_type" class="form-control" required>
                        <option value="gasoline" @selected(old('fuel_type', $vehicle->fuel_type) === 'gasoline')>Xăng</option>
                        <option value="diesel" @selected(old('fuel_type', $vehicle->fuel_type) === 'diesel')>Dầu</option>
                        <option value="electric" @selected(old('fuel_type', $vehicle->fuel_type) === 'electric')>Điện</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dung tích xi-lanh (cc):</label>
                    <input type="number" name="engine_capacity_cc" class="form-control" min="0" value="{{ old('engine_capacity_cc', $vehicle->engine_capacity_cc) }}">
                </div>
                <div class="form-group">
                    <label>Mức tiêu hao (L/100km):</label>
                    <input type="number" name="fuel_consumption" class="form-control" step="0.1" min="0" value="{{ old('fuel_consumption', $vehicle->fuel_consumption) }}">
                </div>
                <div class="form-group">
                    <label>Giá thuê (VNĐ/ngày):</label>
                    <input type="number" name="price_per_day" class="form-control" min="0" value="{{ old('price_per_day', $vehicle->price_per_day) }}" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái:</label>
                    <select name="status" class="form-control" required>
                        <option value="1" @selected(old('status', $vehicle->status) == 1)>Sẵn sàng</option>
                        <option value="2" @selected(old('status', $vehicle->status) == 2)>Đang cho thuê</option>
                        <option value="3" @selected(old('status', $vehicle->status) == 3)>Đang bảo dưỡng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Địa điểm nhận xe:</label>
                    <input type="text" name="pickup_location" class="form-control" value="{{ old('pickup_location', $vehicle->pickup_location) }}">
                </div>
                <div class="form-group">
                    <label>Ảnh xe:</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <label>Thêm ảnh góc chụp khác:</label>
                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    @if ($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="Ảnh {{ $vehicle->name }}" width="160" style="margin-top: 10px">
                    @endif
                </div>
                <div class="form-group form-wide">
                    <label>Mô tả:</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $vehicle->description) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('admin.vehicles.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-update">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
