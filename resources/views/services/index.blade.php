@extends('layouts.master')
@section('title', 'Danh mục dịch vụ')

@section('content')
    @if (session('error'))
        <div role="alert" style="color: #A04A41; margin-bottom: 15px;">{{ session('error') }}</div>
    @endif
    <header class="page-heading"><div><span class="eyebrow">TIỆN ÍCH ĐỘI XE</span><h1>Dịch vụ bổ sung</h1><p>Bảo hiểm, giao nhận và tiện ích có thể thêm vào đơn thuê.</p></div><a href="{{ route('services.create') }}" class="button button-dark"><i class="fa-solid fa-plus"></i> Thêm dịch vụ</a></header>

    <div class="card-box">
        <table class="table-custom">
            <thead>
            <tr><th>MÃ</th><th>TÊN DỊCH VỤ</th><th>GIÁ</th><th>ĐƠN VỊ</th><th>THAO TÁC</th></tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td><strong>{{ $service->name }}</strong></td>
                    <td><strong>{{ number_format($service->unit_price, 0, ',', '.') }}đ</strong></td>
                    <td>{{ $service->unit_name }}</td>
                    <td class="action-links">
                        <a href="{{ route('services.edit', $service->id) }}" class="edit">Sửa</a>
                        <form method="post" action="{{ route('services.destroy', $service->id) }}" style="display:inline;" onsubmit="return confirm('Xóa dịch vụ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="table-empty" colspan="5">Chưa có dịch vụ bổ sung.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
