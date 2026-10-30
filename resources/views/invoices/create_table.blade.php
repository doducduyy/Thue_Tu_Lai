@extends('layouts.master')
@section('title', 'Xác nhận hóa đơn thuê xe')
@section('content')
    <header class="page-heading"><div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Xác nhận hóa đơn</h1><p>Kiểm tra tổng hợp phí trước khi xuất hóa đơn.</p></div></header>
    <section class="form-container invoice-create-form">
        <div class="invoice-summary"><p><b>Khách hàng:</b> {{ $contract->user->fullname }}</p><p><b>Xe:</b> {{ $contract->vehicle->name }} — {{ $contract->vehicle->license_plate }}</p><p><b>Ngày thuê:</b> {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y H:i') }} –
            {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</p>
        <p><b>Số ngày tính tiền:</b> {{ $rentalDays }} ngày</p></div>

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <input type="hidden" name="contract_id" value="{{ $contract->id }}">
            <input type="hidden" name="invoice_date" value="{{ $invoiceDate }}">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="table-scroll"><table class="invoice-lines">
                <thead>
                <tr>
                    <th>Khoản thu</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><b>Thuê xe</b> — {{ $contract->vehicle->name }}</td><td>{{ $rentalDays }} ngày</td><td>{{ number_format($contract->vehicle->price_per_day, 0, ',', '.') }}đ/ngày</td><td>{{ number_format($vehicleAmount, 0, ',', '.') }}đ</td>
                </tr>
                @foreach($serviceDetails as $detail)
                    @if($detail->service)
                        @php
                            $isDaily = in_array(mb_strtolower((string) $detail->service->unit_name), ['ngày', 'day', 'ngày thuê'], true);
                            $quantity = $detail->quantity * ($isDaily ? $rentalDays : 1);
                        @endphp
                        <tr>
                            <td>{{ $detail->service->name }}</td><td>{{ number_format($quantity, 2) }} {{ $detail->service->unit_name }}</td><td>{{ number_format($detail->service->unit_price, 0, ',', '.') }}đ</td><td>{{ number_format($quantity * $detail->service->unit_price, 0, ',', '.') }}đ</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table></div>
            <footer class="invoice-total"><span>Tổng thanh toán</span><strong>{{ number_format($vehicleAmount + $serviceTotal, 0, ',', '.') }} VNĐ</strong></footer>
            <div class="form-actions"><a class="btn-cancel" href="{{ route('invoices.create') }}">Quay lại</a><button class="btn-save" type="submit">Xác nhận & xuất hóa đơn</button></div>
        </form>
    </section>
@endsection
