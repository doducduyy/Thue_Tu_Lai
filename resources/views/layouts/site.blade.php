<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tự Lái Cuteo — Thuê xe linh hoạt')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="site-body">
    <header class="site-header">
        <a class="site-brand" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-key"></i></span><span>TỰ LÁI CUTEO<small>CAR & BIKE RENTAL</small></span></a>
        <nav class="site-nav" aria-label="Điều hướng chính">
            <a href="{{ route('vehicles.index') }}">Thuê xe</a>
            <a href="{{ route('home') }}#how-it-works">Cách thuê</a>
            @auth
                @if((int) auth()->user()->role === 1)
                    <a href="{{ route('dashboard') }}">Quản trị</a>
                @else
                    <a href="{{ route('customer.home') }}">Tài khoản</a>
                @endif
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="nav-signin nav-button" type="submit">Đăng xuất</button></form>
            @else
                <a class="nav-signin" href="{{ route('admin.login') }}">Đăng nhập</a>
            @endauth
        </nav>
    </header>
    <main>
        @if (session('success'))
            <div class="flash-message" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash-message flash-error" role="alert">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer">
        <a class="site-brand" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-key"></i></span><span>TỰ LÁI CUTEO<small>CAR & BIKE RENTAL</small></span></a>
        <p>Chủ động hành trình. Tự do khám phá.</p>
        <span>© {{ date('Y') }} Tự Lái Cuteo</span>
    </footer>
</body>
</html>
