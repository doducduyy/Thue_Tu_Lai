@extends('layouts.master')
@section('title', 'Gắn dịch vụ cho xe')
@section('content')
    <div class="breadcrumb">Quản lý dịch vụ / <b>gắn dịch vụ cho xe</b></div>
    <div class="card-box" style="max-width: 850px;">
        <div class="form-header-line">Dịch vụ bổ sung theo xe</div>
        <form method="post" action="{{ route('serviceDetails.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Dòng xe:</label>
                    <select name="vehicle_id" class="form-control" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->license_plate }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Dịch vụ:</label>
                    <select name="service_id" class="form-control" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} — {{ number_format($service->unit_price) }}đ/{{ $service->unit_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" class="form-control" min="0.01" step="0.01" value="1" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('serviceDetails.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-save">Gắn dịch vụ</button>
            </div>
        </form>
    </div>
@endsection
