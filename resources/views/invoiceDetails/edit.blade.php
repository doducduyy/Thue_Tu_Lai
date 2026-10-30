@extends('layouts.master')
@section('title', 'Cập nhật khoản thu')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU</span><h1>Cập nhật khoản thu</h1></div></header>
    <section class="form-container"><form method="post" action="{{ route('invoiceDetails.update', ['service_id' => $invoiceDetail->service_id, 'invoice_id' => $invoiceDetail->invoice_id]) }}">@csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group"><label for="service_id">Dịch vụ</label><select id="service_id" name="service_id" class="form-control" required>@foreach($services as $service)<option value="{{ $service->id }}" @selected(old('service_id', $invoiceDetail->service_id) == $service->id)>{{ $service->name }}</option>@endforeach</select></div>
            <div class="form-group"><label for="invoice_id">Hóa đơn</label><select id="invoice_id" name="invoice_id" class="form-control" required>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}" @selected(old('invoice_id', $invoiceDetail->invoice_id) == $invoice->id)>#HD-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</option>@endforeach</select></div>
            <div class="form-group"><label for="quantity">Số lượng</label><input id="quantity" type="number" min="0.01" step="0.01" name="quantity" class="form-control" value="{{ old('quantity', $invoiceDetail->quantity) }}" required></div>
            <div class="form-group"><label for="price">Đơn giá (VNĐ)</label><input id="price" type="number" min="0" name="price" class="form-control" value="{{ old('price', $invoiceDetail->price) }}" required></div>
        </div><p class="form-hint">Thành tiền được tính từ số lượng và đơn giá.</p>
        <div class="form-actions"><a class="btn-cancel" href="{{ route('invoiceDetails.index') }}">Hủy</a><button type="submit" class="btn-update">Cập nhật</button></div>
    </form></section>
@endsection
