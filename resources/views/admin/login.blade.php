<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập — Tự Lái Cuteo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="auth-shell">
        <a class="site-brand auth-brand" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-key"></i></span><span>TỰ LÁI CUTEO<small>CAR & BIKE RENTAL</small></span></a>
        <section class="auth-card">
            <span class="eyebrow">CHÀO MỪNG TRỞ LẠI</span>
            <h1>Đăng nhập</h1>
            <p>Truy cập tài khoản để đặt xe và quản lý chuyến đi.</p>
            @if ($errors->any())<div class="auth-alert" role="alert">{{ $errors->first() }}</div>@endif
            @if(session('success'))<div class="auth-success" role="status">{{ session('success') }}</div>@endif
            <form method="POST" action="{{ route('admin.login.post') }}" class="auth-form">
                @csrf
                <label for="email">Email</label>
                <input class="login-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="ban@email.com" autocomplete="email" required autofocus>
                <label for="password">Mật khẩu</label>
                <input class="login-input" id="password" type="password" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                <button class="login-btn" type="submit">Đăng nhập <i class="fa-solid fa-arrow-right"></i></button>
            </form>
            <p class="auth-footnote">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký khách hàng</a></p>
        </section>
        <a class="auth-back" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Trở về trang chủ</a>
    </main>
</body>
</html>
