@extends('layouts.master')
@section('title', 'Danh sách hợp đồng')
@section('content')
    <header class="page-heading">
        <div><span class="eyebrow">ĐIỀU PHỐI ĐẶT XE</span><h1>Hợp đồng thuê xe</h1><p>Theo dõi thời gian thuê, khách hàng và tiền đặt cọc.</p></div>
        <a class="button button-dark" href="{{ route('contracts.create') }}"><i class="fa-solid fa-plus"></i> Tạo hợp đồng</a>
    </header>
    <section class="admin-table-panel">
        <div class="table-scroll"><table class="data-table">
            <thead>
            <tr>
                <th>MÃ</th><th>KHÁCH HÀNG</th><th>XE THUÊ</th><th>NHẬN XE</th>
                <th>TRẢ XE</th><th>TIỀN CỌC</th><th>TRẠNG THÁI</th><th>THAO TÁC</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contracts as $contract)
                <tr>
                    <td><strong>#HD-{{ str_pad($contract->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $contract->user->fullname ?? 'Khách hàng đã xóa' }}</td>
                    <td><strong>{{ $contract->vehicle->name ?? 'Xe đã xóa' }}</strong><br><small>{{ $contract->vehicle->license_plate ?? '' }}</small></td>
                    <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y H:i') }}</td>
                    <td>{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y H:i') : '—' }}</td>
                    <td><strong>{{ number_format($contract->deposit, 0, ',', '.') }}đ</strong></td>
                    <td><span class="status-chip status-{{ $contract->status == 1 ? 1 : 3 }}">{{ $contract->status == 1 ? 'Đang hiệu lực' : 'Đã kết thúc' }}</span></td>
                    <td><div class="table-actions">
                        <a aria-label="Sửa hợp đồng" href="{{ route('contracts.edit', $contract->id) }}"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Xóa hợp đồng này?')">@csrf @method('DELETE')
                            <button type="submit" aria-label="Xóa hợp đồng"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td class="table-empty" colspan="8">Chưa có hợp đồng thuê xe.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
        <div class="table-footer"><span>{{ $contracts->total() }} hợp đồng</span>{{ $contracts->links() }}</div>
    </section>
@endsection
