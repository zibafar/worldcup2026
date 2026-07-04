<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'مدیریت جام جهانی رشت‌گلد')</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/worldcup/style.css') }}">
    <style>
        .admin-wrap{max-width:1100px;margin:0 auto;padding:24px 16px 50px}
        .admin-nav{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0 24px}
        .admin-nav a{color:#ddd;text-decoration:none;background:#171717;border:1px solid #2b2b2b;border-radius:10px;padding:9px 13px;font-size:13px}
        .admin-card{background:#141414;border:1.5px solid #242424;border-radius:14px;padding:18px;margin-bottom:16px}
        .admin-table{width:100%;border-collapse:collapse;overflow:hidden;border-radius:12px;font-size:13px}
        .admin-table th,.admin-table td{border-bottom:1px solid #242424;padding:11px 10px;text-align:right;vertical-align:middle}
        .admin-table th{color:#f5c842;background:#101010;font-weight:800}
        .admin-form{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;align-items:end}
        .admin-form label{display:flex;flex-direction:column;gap:6px;font-size:12px;color:#aaa}
        .admin-form input,.admin-form select,.admin-form textarea{background:#0e0e0e;border:1.5px solid #2a2a2a;color:#fff;border-radius:9px;padding:10px;font-family:var(--font)}
        .admin-form textarea{min-height:95px;grid-column:1/-1}
        .admin-btn{border:none;border-radius:9px;background:var(--green);color:#000;font-family:var(--font);font-weight:800;padding:10px 15px;cursor:pointer;text-decoration:none;display:inline-block}
        .admin-btn.gold{background:linear-gradient(135deg,#f5c842,#c9a227)}
        .admin-btn.ghost{background:#101010;color:#ddd;border:1px solid #333}
        .status{display:inline-block;border-radius:999px;padding:3px 9px;font-size:11px;background:#222;color:#ddd}
        .flash{border-radius:10px;padding:12px 14px;margin-bottom:14px;font-size:13px}.flash.success{background:rgba(0,230,118,.08);border:1px solid rgba(0,230,118,.25);color:var(--green)}.flash.error{background:rgba(231,76,60,.08);border:1px solid rgba(231,76,60,.25);color:#ff8880}
    </style>
    @stack('styles')
</head>
<body>
    <nav>
        <a class="nav-logo" href="{{ route('admin.worldcup.dashboard') }}">
            <div class="logo-coin">RG</div>
            <div class="logo-txt"><span class="l1">مدیریت رشت‌گلد</span><span class="l2">WorldCup Panel</span></div>
        </a>
        <div class="nav-right"><a class="btn-nav btn-ghost" href="{{ route('worldcup.index') }}">مشاهده سایت</a></div>
    </nav>

    <main class="admin-wrap">
        <div class="admin-nav">
            <a href="{{ route('admin.worldcup.dashboard') }}">داشبورد</a>
            <a href="{{ route('admin.worldcup.matches') }}">بازی‌ها</a>
            <a href="{{ route('admin.worldcup.leaderboard') }}">رده‌بندی</a>
            <a href="{{ route('admin.worldcup.scoring-rules') }}">قوانین امتیاز</a>
            <a href="{{ route('admin.worldcup.prizes') }}">جوایز</a>
            <a href="{{ route('admin.worldcup.faqs') }}">FAQ</a>
        </div>

        @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="flash error">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="flash error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
