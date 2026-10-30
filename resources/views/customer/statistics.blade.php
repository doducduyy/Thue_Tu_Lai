@extends('layouts.site')
@section('title', 'Lịch sử thuê xe — Tự Lái Cuteo')
@section('content')
    <section class="customer-hero customer-hero-compact">
        <div><span class="eyebrow eyebrow-light">HÀNH TRÌNH ĐÃ QUA</span><h1>Lịch sử thuê xe</h1><p>Tổng hợp các hóa đơn và chi phí thuê xe của bạn.</p></div>
        <a class="button button-light" href="{{ route('customer.home') }}"><i class="fa-solid fa-arrow-left"></i> Về tài khoản</a>
    </section>
    <div class="customer-shell">
        @if($contract)
            <section class="customer-panel history-summary">
                <span class="eyebrow">HỢP ĐỒNG GẦN NHẤT</span>
                <h2>{{ $contract->vehicle->name ?? 'Xe đã xóa' }}</h2>
                <p>{{ $contract->vehicle->license_plate ?? '' }} <span>·</span>
                    {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</p>
            </section>
        @endif
        <section class="customer-panel">
            <header class="customer-panel-heading"><div><span class="eyebrow">THANH TOÁN</span><h2>Hóa đơn và chi phí</h2></div><a class="text-link" href="{{ route('customer.invoices') }}">Tất cả hóa đơn <i class="fa-solid fa-arrow-right"></i></a></header>
            @if($invoices->isNotEmpty())
                <div class="table-scroll"><table class="customer-table">
                    <thead><tr><th>NGÀY LẬP</th><th>SỐ NGÀY</th><th>TIỀN XE</th><th>DỊCH VỤ</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th></tr></thead>
                    <tbody>@foreach($invoices as $invoice)
                        <tr><td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td><td>{{ $invoice->rental_days }} ngày</td><td>{{ number_format($invoice->vehicle_amount, 0, ',', '.') }}đ</td><td>@forelse($invoice->invoiceDetails as $detail)<span class="invoice-service">{{ $detail->service->name ?? 'Dịch vụ' }}: {{ number_format($detail->amount, 0, ',', '.') }}đ</span>@empty<span>—</span>@endforelse</td><td><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}đ</strong></td><td><span class="status-chip status-{{ $invoice->status == 1 ? 1 : 2 }}">{{ $invoice->status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></td></tr>
                    @endforeach</tbody>
                </table></div>
            @else
                <div class="empty-state"><i class="fa-solid fa-clock-rotate-left"></i><h3>Chưa có lịch sử chuyến thuê</h3><p>Các chuyến đi đã hoàn tất sẽ được tổng hợp tại đây.</p></div>
            @endif
        </section>
    </div>
@endsection
