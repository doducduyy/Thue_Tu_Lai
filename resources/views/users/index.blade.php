@extends('layouts.master')
@section('title', 'Quản lý khách thuê')
@section('content')
    <header class="page-heading">
        <div><span class="eyebrow">HỒ SƠ & TÀI KHOẢN</span><h1>Khách hàng</h1><p>Thông tin liên hệ và trạng thái tài khoản thuê xe.</p></div>
        <a class="button button-dark" href="{{ route('users.create') }}"><i class="fa-solid fa-user-plus"></i> Thêm khách hàng</a>
    </header>
    <section class="admin-table-panel"><div class="table-scroll"><table class="data-table">
            <thead>
            <tr>
                <th>KHÁCH HÀNG</th><th>LIÊN HỆ</th><th>CCCD</th><th>ĐỊA CHỈ</th><th>VAI TRÒ</th><th>TRẠNG THÁI</th><th>THAO TÁC</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->fullname }}</strong><br><small>{{ $user->email }}</small></td>
                    <td>{{ $user->phone }}</td><td>{{ $user->id_card }}</td><td>{{ $user->address }}</td>
                    <td>{{ $user->role == 0 ? 'Khách hàng' : 'Quản trị viên' }}</td>
                    <td><span class="status-chip status-{{ $user->status == 1 ? 1 : 3 }}">{{ $user->status == 1 ? 'Hoạt động' : 'Đã khóa' }}</span></td>
                    <td><div class="table-actions">
                        <a aria-label="Sửa hồ sơ" href="{{ route('users.edit', $user->id) }}"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Xóa hồ sơ khách hàng này?')">@csrf @method('DELETE')
                            <button type="submit" aria-label="Xóa hồ sơ"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="7" class="table-empty">Chưa có khách hàng nào.</td></tr>
            @endforelse
            </tbody>
        </table></div><div class="table-footer"><span>{{ $users->total() }} khách hàng</span>{{ $users->links() }}</div>
    </section>
@endsection
