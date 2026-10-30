@extends('layouts.master')
@section('title', 'Sửa dịch vụ xe')
@section('content')
    <div class="breadcrumb">Quản lý dịch vụ / <b>sửa cấu hình dịch vụ xe</b></div>
    <div class="card-box" style="max-width: 800px;">
        <div class="form-header-line">Cập nhật dịch vụ bổ sung</div>
        <form method="post" action="{{ route('serviceDetails.update', [$serviceDetail->vehicle_id, $serviceDetail->service_id]) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Dòng xe:</label>
                    <select name="vehicle_id" class="form-control" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected($serviceDetail->vehicle_id == $vehicle->id)>{{ $vehicle->name }} ({{ $vehicle->license_plate }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Dịch vụ:</label>
                    <select name="service_id" class="form-control" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected($serviceDetail->service_id == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" class="form-control" min="0.01" step="0.01" value="{{ $serviceDetail->quantity }}" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('serviceDetails.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-update">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
