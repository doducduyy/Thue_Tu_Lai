@extends('layouts.master')
@section('title', 'Chi tiết hóa đơn #' . $invoice->id)
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Hóa đơn #HD-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h1><p>Ngày lập {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p></div><a class="button button-outline" href="{{ route('invoices.index') }}"><i class="fa-solid fa-arrow-left"></i> Danh sách hóa đơn</a></header>
    <article class="invoice-document">
        <header class="invoice-document-heading"><span class="brand-mark"><i class="fa-solid fa-key"></i></span><div><span class="eyebrow">TỰ LÁI CUTEO · CAR & BIKE RENTAL</span><h2>Chi tiết thanh toán</h2></div><span class="status-chip status-{{ $invoice->status == 1 ? 1 : 2 }}">{{ $invoice->status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></header>
        <dl class="invoice-facts">
            <div><dt>Khách hàng</dt><dd>{{ $invoice->contract->user->fullname ?? 'N/A' }}</dd><small>{{ $invoice->contract->user->phone ?? '' }}</small></div>
            <div><dt>Hợp đồng</dt><dd>#HD-{{ str_pad($invoice->contract_id, 5, '0', STR_PAD_LEFT) }}</dd></div>
            <div><dt>Xe thuê</dt><dd>{{ $invoice->vehicle->name ?? 'Xe đã xóa' }}</dd><small>{{ $invoice->vehicle->license_plate ?? '' }}</small></div>
            <div><dt>Thời gian thuê</dt><dd>{{ $invoice->rental_days }} ngày</dd></div>
        </dl>
        <div class="table-scroll"><table class="invoice-lines">
            <thead><tr><th>Khoản thu</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
            <tbody>
                <tr><td>Thuê xe — {{ $invoice->vehicle->name ?? 'Xe đã xóa' }}</td><td>{{ $invoice->rental_days }} ngày</td><td>{{ number_format($invoice->rental_days ? $invoice->vehicle_amount / $invoice->rental_days : 0, 0, ',', '.') }}đ/ngày</td><td>{{ number_format($invoice->vehicle_amount, 0, ',', '.') }}đ</td></tr>
                @foreach($invoice->invoiceDetails as $detail)
                    <tr><td>{{ $detail->service->name ?? 'Dịch vụ đã xóa' }}</td><td>{{ number_format($detail->quantity, 2) }} {{ $detail->service->unit_name ?? '' }}</td><td>{{ number_format($detail->price, 0, ',', '.') }}đ</td><td>{{ number_format($detail->amount, 0, ',', '.') }}đ</td></tr>
                @endforeach
            </tbody>
        </table></div>
        <footer class="invoice-total"><span>Tổng thanh toán</span><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }} VNĐ</strong></footer>
        @if($invoice->status != 1)
            <form class="invoice-pay-action" action="{{ route('invoices.confirmPayment', $invoice->id) }}" method="POST">@csrf<button class="button button-dark" type="submit"><i class="fa-solid fa-check"></i> Xác nhận đã thanh toán</button></form>
        @endif
    </article>
@endsection
