@extends('layouts.master')
@section('title', 'Tạo hóa đơn thuê xe')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Lập hóa đơn thuê xe</h1><p>Chọn hợp đồng để tính tiền thuê xe và dịch vụ phát sinh.</p></div></header>
    <div class="form-container invoice-create-form">
        <div class="form-subtitle">THÔNG TIN HÓA ĐƠN</div>
        @if ($errors->any())<div class="alert" role="alert">{{ $errors->first() }}</div>@endif
        <form action="{{ route('invoices.createStep2') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="contract_id">Hợp đồng đặt xe:</label>
                <select id="contract_id" name="contract_id" class="form-control" required>
                    <option value="">-- Chọn hợp đồng --</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" @selected(old('contract_id') == $contract->id)>
                            {{ $contract->vehicle->name }} ({{ $contract->vehicle->license_plate }}) — {{ $contract->user->fullname }}
                        </option>
                    @endforeach
                </select>
                @if($contracts->isEmpty())
                    <p>Không có hợp đồng nào cần xuất hóa đơn.</p>
                @endif
            </div>
            <div class="form-group">
                <label for="invoice_date">Ngày lập hóa đơn:</label>
                <input id="invoice_date" type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Trạng thái thanh toán:</label>
                <select id="status" name="status" class="form-control">
                    <option value="0" @selected(old('status', 0) == 0)>Chưa thanh toán</option>
                    <option value="1" @selected(old('status') == 1)>Đã thanh toán</option>
                </select>
            </div>
            <div class="form-actions"><a class="btn-cancel" href="{{ route('invoices.index') }}">Hủy</a><button type="submit" class="btn-save" @disabled($contracts->isEmpty())>Tính hóa đơn <i class="fa-solid fa-arrow-right"></i></button></div>
        </form>
    </div>
@endsection
