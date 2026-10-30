@extends('layouts.master')
@section('title', 'Chi tiết hóa đơn')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Khoản thu theo hóa đơn</h1><p>Chi tiết các dịch vụ và khoản phí bổ sung.</p></div><a class="button button-dark" href="{{ route('invoiceDetails.create') }}"><i class="fa-solid fa-plus"></i> Thêm khoản thu</a></header>
    <section class="admin-table-panel"><div class="table-scroll"><table class="data-table">
        <thead><tr><th>DỊCH VỤ</th><th>HÓA ĐƠN</th><th>SỐ LƯỢNG</th><th>ĐƠN GIÁ</th><th>THÀNH TIỀN</th><th>THAO TÁC</th></tr></thead>
        <tbody>@forelse($invoiceDetails as $invoiceDetail)
            <tr><td><strong>{{ $invoiceDetail->service->name ?? 'Dịch vụ đã xóa' }}</strong></td>
                <td>#HD-{{ str_pad($invoiceDetail->invoice->id ?? $invoiceDetail->invoice_id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $invoiceDetail->quantity }}</td><td>{{ number_format($invoiceDetail->price, 0, ',', '.') }}đ</td><td><strong>{{ number_format($invoiceDetail->amount, 0, ',', '.') }}đ</strong></td>
                <td><div class="table-actions">
                    <a aria-label="Sửa khoản thu" href="{{ route('invoiceDetails.edit', ['service_id' => $invoiceDetail->service_id, 'invoice_id' => $invoiceDetail->invoice_id]) }}"><i class="fa-solid fa-pen"></i></a>
                    <form method="post" action="{{ route('invoiceDetails.destroy', ['service_id' => $invoiceDetail->service_id, 'invoice_id' => $invoiceDetail->invoice_id]) }}" onsubmit="return confirm('Xóa khoản thu này?')">@csrf @method('DELETE')<button type="submit" aria-label="Xóa khoản thu"><i class="fa-solid fa-trash"></i></button></form>
                </div></td>
            </tr>
        @empty<tr><td class="table-empty" colspan="6">Chưa có khoản thu nào.</td></tr>@endforelse</tbody>
    </table></div></section>
@endsection
