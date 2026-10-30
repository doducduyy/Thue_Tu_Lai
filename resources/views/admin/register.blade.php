<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký — Tự Lái Cuteo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="auth-shell">
        <a class="site-brand auth-brand" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-key"></i></span><span>TỰ LÁI CUTEO<small>CAR & BIKE RENTAL</small></span></a>
        <section class="auth-card">
            <span class="eyebrow">BẮT ĐẦU HÀNH TRÌNH MỚI</span>
            <h1>Tạo tài khoản</h1>
            <p>Đăng ký để gửi yêu cầu thuê xe và quản lý chuyến đi.</p>
            @if ($errors->any())<div class="auth-alert" role="alert">{{ $errors->first() }}</div>@endif
            <form class="auth-form" method="POST" action="{{ route('register.post') }}">
                @csrf
                <label for="fullname">Họ và tên</label><input class="login-input" id="fullname" type="text" name="fullname" value="{{ old('fullname') }}" autocomplete="name" required>
                <label for="email">Email</label><input class="login-input" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                <label for="phone">Số điện thoại</label><input class="login-input" id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="10" required>
                <label for="id_card">Số CCCD</label><input class="login-input" id="id_card" type="text" name="id_card" value="{{ old('id_card') }}" maxlength="12" required>
                <label for="address">Địa chỉ</label><input class="login-input" id="address" type="text" name="address" value="{{ old('address') }}" autocomplete="street-address" required>
                <label for="password">Mật khẩu</label><input class="login-input" id="password" type="password" name="password" autocomplete="new-password" required>
                <button class="login-btn" type="submit">Tạo tài khoản <i class="fa-solid fa-arrow-right"></i></button>
            </form>
            <p class="auth-footnote">Đã có tài khoản? <a href="{{ route('admin.login') }}">Đăng nhập</a></p>
        </section>
        <a class="auth-back" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Trở về trang chủ</a>
    </main>
</body>
</html>
