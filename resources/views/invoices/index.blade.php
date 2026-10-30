@extends('layouts.master')
@section('title', 'Quản lý hóa đơn')
@section('content')
    <header class="page-heading">
        <div><span class="eyebrow">DOANH THU & THANH TOÁN</span><h1>Hóa đơn</h1><p>Quản lý hóa đơn theo từng hợp đồng thuê xe.</p></div>
        <a class="button button-dark" href="{{ route('invoices.create') }}"><i class="fa-solid fa-plus"></i> Lập hóa đơn</a>
    </header>
    <section class="admin-table-panel"><div class="table-scroll"><table class="data-table">
            <thead>
            <tr>
                <th>MÃ</th><th>NGÀY LẬP</th><th>XE THUÊ</th><th>KHÁCH HÀNG</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th><th>THAO TÁC</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td><strong>#HD-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                    <td><strong>{{ $invoice->vehicle->name ?? 'Xe đã xóa' }}</strong><br><small>{{ $invoice->vehicle->license_plate ?? '' }}</small></td>
                    <td>{{ $invoice->contract->user->fullname ?? 'Khách hàng đã xóa' }}</td>
                    <td><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}đ</strong></td>
                    <td><span class="status-chip status-{{ $invoice->status == 1 ? 1 : 2 }}">{{ $invoice->status == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></td>
                    <td><div class="table-actions">
                        <a aria-label="Xem hóa đơn" href="{{ route('invoices.show', $invoice) }}"><i class="fa-solid fa-eye"></i></a>
                        <a aria-label="Sửa hóa đơn" href="{{ route('invoices.edit', $invoice) }}"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Xóa hóa đơn này?')">@csrf @method('DELETE')
                            <button type="submit" aria-label="Xóa hóa đơn"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td class="table-empty" colspan="7">Chưa có hóa đơn nào.</td></tr>
            @endforelse
            </tbody>
        </table></div><div class="table-footer"><span>{{ $invoices->total() }} hóa đơn</span>{{ $invoices->links() }}</div>
    </section>
@endsection
