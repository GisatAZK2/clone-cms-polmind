<!DOCTYPE html>
<html lang="id">

<head>
   <script>
    (function () {
        // Theme
        var theme = localStorage.getItem('admin_theme') || 'light';

        if (theme === 'system') {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.style.colorScheme = theme;

        // Sidebar state
        if (localStorage.getItem('sidebar_collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }

        // Sidebar scroll prepaint
        var sidebarScroll = localStorage.getItem('admin_sidebar_scroll_top');

        if (sidebarScroll !== null) {
            var parsedScroll = parseInt(sidebarScroll, 10) || 0;

            document.documentElement.classList.add('sidebar-scroll-prepaint');
            document.documentElement.style.setProperty('--sidebar-prepaint-scroll', '-' + parsedScroll + 'px');
        }

        // Language
        var lang = localStorage.getItem('admin_language') || 'id';

        document.documentElement.setAttribute('data-lang', lang);
        document.documentElement.setAttribute('lang', lang);
    })();
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - CMS Polmind')</title>
    <meta property="og:description"
        content="Politeknik Mitra Industri Dikembangkan di kawasan industri MM2100, didukung oleh para praktisi industri dan pendidikan.">
    <meta property="og:url" content="https://polmind.ac.id/">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon-cerah.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-style.css') }}">
    <script src="{{ asset('assets/js/adminTranslate.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Sweet Alert 2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Theme System -->
    <script src="{{ asset('assets/js/adminTheme.js') }}"></script>

    @yield('style')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="main-content">

            @include('admin.components.header')

            <!-- Page Content -->
            <div class="page-content">
               @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
                        <strong data-translate="common.validationError">Terjadi Kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success_key'))
                    <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert">
                        <span
                            data-translate="{{ session('success_key') }}"
                            data-translate-params='@json(session('success_params', []))'
                        >
                            {{ session('success_key') }}
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @elseif (session('success'))
                    <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error_key'))
                    <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
                        <span
                            data-translate="{{ session('error_key') }}"
                            data-translate-params='@json(session('error_params', []))'
                        >
                            {{ session('error_key') }}
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            @include('admin.components.footer')
        </div>
    </div>

    <!-- JS Import -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sweet Alert 2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <!-- (No external CropperJS - using custom crop implementation) -->
    <!-- Translation System -->
    <script src="{{ asset('assets/js/alert.js') }}"></script>
    <script src="{{ asset('assets/js/image-modal.js') }}"></script>
    <script src="{{ asset('assets/js/remove-bg.js') }}"></script>
    <script src="{{ asset('assets/js/image-ratio.js') }}"></script>
    <script src="{{ asset('assets/js/admin-script.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.auto-dismiss-alert');

            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.classList.add('is-hiding');

                    setTimeout(function () {
                        const bootstrapAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bootstrapAlert.close();
                    }, 350);
                }, 3000);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>