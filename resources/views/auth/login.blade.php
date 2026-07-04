<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود ادمین | رشت‌گلد</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Tahoma, Arial, sans-serif;
            background: #0a0a0a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 390px;
            background: #141414;
            border: 1px solid #252525;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 20px 70px rgba(0, 0, 0, .35);
        }

        .logo {
            width: 54px;
            height: 54px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5c842, #c9a227);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
        }

        h1 {
            margin: 0 0 8px;
            text-align: center;
            font-size: 22px;
        }

        p {
            margin: 0 0 24px;
            text-align: center;
            color: #888;
            font-size: 13px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            color: #ccc;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            background: #0d0d0d;
            border: 1px solid #2d2d2d;
            color: #fff;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #00e676;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: #aaa;
            font-size: 13px;
        }

        .remember input {
            width: auto;
            margin: 0;
        }

        button {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 13px;
            background: #00e676;
            color: #000;
            font-weight: 800;
            cursor: pointer;
            font-size: 15px;
        }

        .error {
            background: rgba(255, 60, 60, .1);
            border: 1px solid rgba(255, 60, 60, .25);
            color: #ff8080;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 13px;
            line-height: 1.8;
        }

        .back {
            display: block;
            text-align: center;
            color: #f5c842;
            text-decoration: none;
            margin-top: 18px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo">RG</div>

    <h1>ورود ادمین</h1>
    <p>پنل مدیریت پیش‌بینی جام جهانی رشت‌گلد</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <label for="email">ایمیل</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="admin@rashtgold.test"
            required
            autofocus
        >

        <label for="password">رمز عبور</label>
        <input
            id="password"
            type="password"
            name="password"
            placeholder="رمز عبور"
            required
        >

        <label class="remember">
            <input type="checkbox" name="remember" value="1">
            مرا به خاطر بسپار
        </label>

        <button type="submit">ورود به پنل</button>
    </form>

    <a class="back" href="{{ route('worldcup.index') }}">بازگشت به صفحه مسابقه</a>
</div>

</body>
</html>
