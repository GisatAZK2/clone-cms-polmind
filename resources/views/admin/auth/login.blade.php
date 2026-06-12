<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-page="login.title">Login — CMS Polmind</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon-cerah.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-style.css') }}">
</head>

<body class="login-page">

    <div class="login-card">

        <div class="login-head">
            <div class="login-page-logo">
                <img src="{{ asset('assets/images/favicon-polmind.ico') }}" alt="Logo Polmind" class="login-logo preview-image">
                <span class="login-brand-name">CMS Polmind</span>
            </div>
            <h1 data-translate="login.welcome">Selamat datang</h1>
            <p data-translate="login.description">Masuk untuk melanjutkan ke panel admin.</p>
        </div>

        <div class="login-body">

            @if ($errors->any())
                <div class="login-alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        <strong data-translate="login.errorHeader">Login gagal.</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="login-alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="login-field">
                    <label for="email" data-translate="login.emailLabel">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope ico"></i>
                        <input type="email" id="email" name="email" placeholder="nama@contoh.com"
                            data-translate="login.emailPlaceholder"
                            value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required autofocus>
                    </div>
                    @error('email')
                        <div class="field-error"><i class="bi bi-x-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-field">
                    <label for="password" data-translate="login.passwordLabel">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock ico"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••"
                            data-translate="login.passwordPlaceholder"
                            class="@error('password') is-invalid @enderror" required>
                        <button type="button" class="btn-pwd-toggle" onclick="togglePwd()" aria-label="Toggle password">
                            <i class="bi bi-eye" id="pwdIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="bi bi-x-circle"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="row-check">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="check-label" for="remember" data-translate="login.rememberMe">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login-submit">
                    <i class="bi bi-box-arrow-in-right"></i> <span data-translate="login.submit">Masuk</span>
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/js/admin-script.js') }}"></script>
    <script src="{{ asset('assets/js/adminTranslate.js') }}"></script>
</body>

</html>