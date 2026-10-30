@extends('layouts.master')
@section('title', 'Ghi nhận thanh toán')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU</span><h1>Ghi nhận thanh toán</h1><p>Chọn hóa đơn và phương thức nhận tiền.</p></div></header>
    <section class="form-container">
        <form method="post" action="{{ route('payments.store') }}">@csrf
            <div class="form-grid">
                <div class="form-group"><label for="invoice_id">Hóa đơn</label><select id="invoice_id" name="invoice_id" class="form-control" required>
                    <option value="">-- Chọn hóa đơn --</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" @selected(old('invoice_id') == $invoice->id)>#HD-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }} · {{ $invoice->contract?->user?->fullname ?? 'Khách hàng' }} · {{ number_format($invoice->total_amount) }}đ</option>
                    @endforeach
                </select></div>
                <div class="form-group"><label for="payment_method_id">Phương thức thanh toán</label><select id="payment_method_id" name="payment_method_id" class="form-control" required>
                    <option value="">-- Chọn phương thức --</option>
                    @foreach($paymentMethods as $paymentMethod)<option value="{{ $paymentMethod->id }}" @selected(old('payment_method_id') == $paymentMethod->id)>{{ $paymentMethod->name ?? 'Phương thức ' . $paymentMethod->id }}</option>@endforeach
                </select></div>
                <div class="form-group"><label for="amount">Số tiền (VNĐ)</label><input id="amount" type="number" min="0" step="1" name="amount" class="form-control" value="{{ old('amount') }}" required></div>
                <div class="form-group"><label for="payment_date">Ngày thanh toán</label><input id="payment_date" type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required></div>
            </div>
            <div class="form-actions"><a class="btn-cancel" href="{{ route('payments.index') }}">Hủy</a><button type="submit" class="btn-save">Lưu giao dịch</button></div>
        </form>
    </section>
@endsection
