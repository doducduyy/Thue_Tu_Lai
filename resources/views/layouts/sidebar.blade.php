<aside class="sidebar">
    <div class="logo">
        <span></span>
        <h2>TỰ LÁI CUTEO<br><small>CONTROL CENTER</small></h2>
    </div>
    <nav class="menu" aria-label="Điều hướng quản trị">
        <ul>
            <li><a href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}"><i class="fa-solid fa-chart-pie"></i><span>Tổng quan</span></a></li>
            <li><a href="{{ route('admin.vehicles.index') }}" aria-current="{{ request()->routeIs('admin.vehicles.*') ? 'page' : 'false' }}"><i class="fa-solid fa-car-side"></i><span>Quản lý đội xe</span></a></li>
            <li><a href="{{ route('users.index') }}" aria-current="{{ request()->routeIs('users.*') ? 'page' : 'false' }}"><i class="fa-solid fa-users"></i><span>Khách hàng</span></a></li>
            <li><a href="{{ route('contracts.index') }}" aria-current="{{ request()->routeIs('contracts.*') ? 'page' : 'false' }}"><i class="fa-solid fa-file-signature"></i><span>Đặt xe & hợp đồng</span></a></li>
            <li><a href="{{ route('invoices.index') }}" aria-current="{{ request()->routeIs('invoices.*') ? 'page' : 'false' }}"><i class="fa-solid fa-receipt"></i><span>Hóa đơn</span></a></li>
            <li><a href="{{ route('payments.index') }}" aria-current="{{ request()->routeIs('payments.*') ? 'page' : 'false' }}"><i class="fa-solid fa-credit-card"></i><span>Giao dịch</span></a></li>
            <li><a href="{{ route('payment_methods.index') }}" aria-current="{{ request()->routeIs('payment_methods.*') ? 'page' : 'false' }}"><i class="fa-solid fa-wallet"></i><span>Phương thức thanh toán</span></a></li>
            <li><a href="{{ route('services.index') }}" aria-current="{{ request()->routeIs('services.*') ? 'page' : 'false' }}"><i class="fa-solid fa-concierge-bell"></i><span>Dịch vụ</span></a></li>
            <li><a href="{{ route('serviceDetails.index') }}" aria-current="{{ request()->routeIs('serviceDetails.*') ? 'page' : 'false' }}"><i class="fa-solid fa-link"></i><span>Dịch vụ theo xe</span></a></li>
            <li><a href="{{ route('invoiceDetails.index') }}" aria-current="{{ request()->routeIs('invoiceDetails.*') ? 'page' : 'false' }}"><i class="fa-solid fa-list-check"></i><span>Chi tiết hóa đơn</span></a></li>
            <li><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-up-right-from-square"></i><span>Xem trang thuê xe</span></a></li>
        </ul>
    </nav>
    <div class="logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</button>
        </form>
    </div>
</aside>
