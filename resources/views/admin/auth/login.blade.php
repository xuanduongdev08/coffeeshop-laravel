<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập Quản trị — XDTHECOFFEEHOUSE</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --coffee-dark: #1e130c;
            --coffee: #6f4e37;
            --coffee-light: #8b6f47;
            --coffee-pale: #c49b63;
            --coffee-cream: #f5efe6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e130c 0%, #2c1810 30%, #3d2216 60%, #1e130c 100%);
            position: relative;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(196, 155, 99, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(196, 155, 99, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(196, 155, 99, 0.03) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Floating coffee beans decoration */
        .coffee-bean {
            position: fixed;
            opacity: 0.04;
            font-size: 80px;
            animation: float 20s infinite ease-in-out;
            pointer-events: none;
            z-index: 0;
        }
        .coffee-bean:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; font-size: 60px; }
        .coffee-bean:nth-child(2) { top: 70%; right: 10%; animation-delay: -5s; font-size: 100px; }
        .coffee-bean:nth-child(3) { bottom: 15%; left: 15%; animation-delay: -10s; font-size: 70px; }
        .coffee-bean:nth-child(4) { top: 30%; right: 20%; animation-delay: -15s; font-size: 50px; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(-10px) rotate(-3deg); }
            75% { transform: translateY(-25px) rotate(2deg); }
        }

        /* Login container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        /* Login card */
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(196, 155, 99, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card header */
        .login-header {
            background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee) 100%);
            padding: 36px 40px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--coffee-pale), transparent);
        }

        .login-logo {
            font-size: 42px;
            margin-bottom: 10px;
            display: block;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }

        .login-header h1 {
            color: var(--coffee-pale);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            font-weight: 400;
        }

        /* Card body */
        .login-body {
            padding: 36px 40px 40px;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #555;
            margin-bottom: 7px;
            letter-spacing: 0.3px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #bbb;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 2px solid #e8e4df;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #333;
            background: #fafaf8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--coffee-pale);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(196, 155, 99, 0.1);
        }

        .form-group input:focus ~ .input-icon,
        .form-group input:focus + .input-icon {
            color: var(--coffee-pale);
        }

        .form-group input::placeholder {
            color: #c0b8ae;
        }

        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #bbb;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.3s;
        }
        .toggle-password:hover {
            color: var(--coffee);
        }

        /* Remember me */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--coffee);
            cursor: pointer;
        }

        .remember-me span {
            font-size: 13px;
            color: #777;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--coffee);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .forgot-link:hover {
            color: var(--coffee-pale);
            text-decoration: underline;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--coffee) 0%, var(--coffee-light) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Error messages */
        .error-message {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-message .error-icon {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .error-message ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-message li {
            font-size: 13px;
            color: #c53030;
            line-height: 1.5;
        }

        /* Back to site */
        .back-to-site {
            text-align: center;
            margin-top: 24px;
        }

        .back-to-site a {
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-to-site a:hover {
            color: var(--coffee-pale);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-header { padding: 28px 24px 24px; }
            .login-body { padding: 28px 24px 32px; }
            .login-header h1 { font-size: 16px; }
        }
    </style>
</head>
<body>

<!-- Decorative coffee beans -->
<span class="coffee-bean">☕</span>
<span class="coffee-bean">☕</span>
<span class="coffee-bean">☕</span>
<span class="coffee-bean">☕</span>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <span class="login-logo">☕</span>
            <h1>XDTHECOFFEEHOUSE</h1>
            <p>Đăng nhập vào Bảng quản trị</p>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="error-message">
                    <span class="error-icon">⚠️</span>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="on">
                @csrf

                <div class="form-group">
                    <label for="email">Địa chỉ Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="admin@xdthecoffeehouse.com" required autofocus autocomplete="email">
                        <span class="input-icon">📧</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password"
                            placeholder="Nhập mật khẩu..." required autocomplete="current-password">
                        <span class="input-icon">🔒</span>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <span id="toggleIcon">👁️</span>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    Đăng nhập Quản trị
                </button>
            </form>
        </div>
    </div>

    <div class="back-to-site">
        <a href="{{ route('home') }}">
            ← Quay lại trang chủ
        </a>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

// Auto-focus animation
document.addEventListener('DOMContentLoaded', function() {
    var emailInput = document.getElementById('email');
    if (emailInput && !emailInput.value) {
        setTimeout(function() { emailInput.focus(); }, 600);
    }
});
</script>

</body>
</html>
