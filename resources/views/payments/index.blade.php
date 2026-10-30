@extends('layouts.master')
@section('title', 'Giao dịch thanh toán')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Giao dịch</h1><p>Các khoản thanh toán gắn với hóa đơn thuê xe.</p></div><a class="button button-dark" href="{{ route('payments.create') }}"><i class="fa-solid fa-plus"></i> Ghi nhận giao dịch</a></header>
    <section class="admin-table-panel"><div class="table-scroll"><table class="data-table">
        <thead><tr><th>MÃ</th><th>HÓA ĐƠN</th><th>PHƯƠNG THỨC</th><th>NGÀY THANH TOÁN</th><th>SỐ TIỀN</th><th>THAO TÁC</th></tr></thead>
        <tbody>
        @forelse($payments as $payment)
            <tr>
                <td><strong>#{{ $payment->id }}</strong></td>
                <td><a href="{{ route('invoices.show', $payment->invoice) }}">#HD-{{ str_pad($payment->invoice->id, 5, '0', STR_PAD_LEFT) }}</a></td>
                <td>{{ $payment->paymentMethod->name ?? 'Không xác định' }}</td>
                <td>{{ $payment->payment_date }}</td>
                <td><strong>{{ number_format($payment->amount, 0, ',', '.') }}đ</strong></td>
                <td><div class="table-actions"><a aria-label="Sửa giao dịch" href="{{ route('payments.edit', $payment->id) }}"><i class="fa-solid fa-pen"></i></a>
                    <form method="post" action="{{ route('payments.destroy', $payment->id) }}" onsubmit="return confirm('Xóa giao dịch này?')">@csrf @method('DELETE')<button type="submit" aria-label="Xóa giao dịch"><i class="fa-solid fa-trash"></i></button></form>
                </div></td>
            </tr>
        @empty
            <tr><td class="table-empty" colspan="6">Chưa có giao dịch thanh toán.</td></tr>
        @endforelse
        </tbody>
    </table></div></section>
@endsection
