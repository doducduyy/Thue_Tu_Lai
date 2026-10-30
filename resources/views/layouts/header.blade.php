<header class="header">
    <div class="header-left">
        <span>TRANG QUẢN TRỊ</span>
    </div>
    <div class="header-right"><span class="user-avatar">{{ mb_substr(auth()->user()->fullname ?? 'A', 0, 1) }}</span><span>{{ auth()->user()->fullname ?? 'Quản trị viên' }}</span></div>
</header>
