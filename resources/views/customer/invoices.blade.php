@extends('layouts.site')
@section('title', 'Hóa đơn của tôi — Tự Lái Cuteo')
@section('content')
    <section class="customer-hero customer-hero-compact">
        <div><span class="eyebrow eyebrow-light">CHI PHÍ CHUYẾN ĐI</span><h1>Hóa đơn của tôi</h1><p>Xem tiền thuê xe, dịch vụ đi kèm và trạng thái thanh toán.</p></div>
        <a class="button button-light" href="{{ route('customer.home') }}"><i class="fa-solid fa-arrow-left"></i> Về tài khoản</a>
    </section>
    <div class="customer-shell">
        <section class="customer-panel">
            @if($invoices->isNotEmpty())
                <div class="table-scroll"><table class="customer-table">
                    <thead><tr><th>HÓA ĐƠN</th><th>NGÀY LẬP</th><th>XE THUÊ</th><th>SỐ NGÀY</th><th>DỊCH VỤ</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th></tr></thead>
                    <tbody>@foreach($invoices as $invoice)
                        <tr>
                            <td><strong>#HD-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                            <td>{{ $invoice->vehicle->name ?? 'Xe đã xóa' }}<small>{{ $invoice->vehicle->license_plate ?? '' }}</small></td>
                            <td>{{ $invoice->rental_days }} ngày</td>
                            <td>@forelse($invoice->invoiceDetails as $detail)<span class="invoice-service">{{ $detail->service->name ?? 'Dịch vụ' }}: {{ number_format($detail->amount, 0, ',', '.') }}đ</span>@empty<span>—</span>@endforelse</td>
                            <td><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}đ</strong></td>
                            <td><span class="status-chip status-{{ $invoice->status == 1 ? 1 : 2 }}">{{ $invoice->status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></td>
                        </tr>
                    @endforeach</tbody>
                </table></div>
            @else
                <div class="empty-state"><i class="fa-solid fa-receipt"></i><h3>Chưa có hóa đơn</h3><p>Hóa đơn của các chuyến thuê sẽ được lưu tại đây.</p><a class="button button-dark" href="{{ route('vehicles.index') }}">Tìm xe phù hợp</a></div>
            @endif
        </section>
    </div>
@endsection
