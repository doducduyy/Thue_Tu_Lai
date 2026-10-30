@extends('layouts.master')
@section('title', 'Phương thức thanh toán')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">CẤU HÌNH HỆ THỐNG</span><h1>Phương thức thanh toán</h1><p>Quản lý các lựa chọn thanh toán áp dụng cho hóa đơn.</p></div><a class="button button-dark" href="{{ route('payment_methods.create') }}"><i class="fa-solid fa-plus"></i> Thêm phương thức</a></header>
    <section class="admin-table-panel"><div class="table-scroll"><table class="data-table">
        <thead><tr><th>MÃ</th><th>TÊN PHƯƠNG THỨC</th><th>MÃ THAM CHIẾU</th><th>MÔ TẢ</th><th>THAO TÁC</th></tr></thead>
        <tbody>@forelse($paymentMethods as $payment_method)
            <tr><td>#{{ $payment_method->id }}</td><td><strong>{{ $payment_method->name }}</strong></td><td>{{ $payment_method->code }}</td><td>{{ $payment_method->description }}</td><td><div class="table-actions">
                <a aria-label="Sửa phương thức" href="{{ route('payment_methods.edit', $payment_method->id) }}"><i class="fa-solid fa-pen"></i></a>
                <form method="post" action="{{ route('payment_methods.destroy', $payment_method->id) }}" onsubmit="return confirm('Xóa phương thức thanh toán?')">@csrf @method('DELETE')<button type="submit" aria-label="Xóa phương thức"><i class="fa-solid fa-trash"></i></button></form>
            </div></td></tr>
        @empty<tr><td class="table-empty" colspan="5">Chưa có phương thức thanh toán.</td></tr>@endforelse</tbody>
    </table></div></section>
@endsection
