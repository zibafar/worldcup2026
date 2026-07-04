<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پیش‌بینی جام جهانی | رشت‌گلد')</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/worldcup/style.css') }}">
    @stack('styles')
</head>
<body>
    @yield('content')

    <script src="{{ asset('assets/worldcup/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
