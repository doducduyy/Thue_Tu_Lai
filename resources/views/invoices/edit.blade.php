@extends('layouts.master')
@section('title', 'Cập nhật trạng thái hóa đơn')
@section('content')
    <div class="page-title">Quản Lý Hóa Đơn / <strong>Hóa đơn #{{ $invoice->id }}</strong></div>
    <div class="form-container">
        <div class="form-subtitle">CẬP NHẬT TRẠNG THÁI THANH TOÁN</div>
        <p>Xe thuê: {{ $invoice->vehicle->name ?? 'N/A' }}</p>
        <p>Ngày lập: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
        <form method="post" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Trạng thái thanh toán:</label>
                <select name="status" class="form-control" required>
                    <option value="0" @selected(old('status', $invoice->status) == 0)>Chưa thanh toán</option>
                    <option value="1" @selected(old('status', $invoice->status) == 1)>Đã thanh toán</option>
                </select>
                @error('status')<div style="color: red">{{ $message }}</div>@enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('invoices.index') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-update">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
