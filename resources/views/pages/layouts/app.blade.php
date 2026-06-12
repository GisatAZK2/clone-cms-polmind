<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E959YP027R"
        onerror="console.warn('gtag.js failed to load')"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-E959YP027R');
    </script>

    <meta name="description"
        content="{{ $meta_description ?? 'Politeknik Mitra Industri Dikembangkan di kawasan industri MM2100, didukung oleh para praktisi industri dan pendidikan.' }}" />
    <meta name="keywords"
        content="{{ $meta_keywords ?? 'politeknik, mitra, industri, polmind, politeknik mitra industri, perguruan tinggi vokasi terbaik, politeknik terbaik' }}" />
    <meta name="site_name" content="Politeknik Mitra Industri">

    <title>{{ $page_title ?? 'Politeknik Mitra Industri' }}</title>
    <link rel="canonical" href="{{ $canonical_url ?? 'https://polmind.ac.id' }}" />

    <!-- Open Graph -->
    <meta property="og:title" content="Politeknik Mitra Industri">
    <meta property="og:description"
        content="Politeknik Mitra Industri Dikembangkan di kawasan industri MM2100, didukung oleh para praktisi industri dan pendidikan.">
    <meta property="og:url" content="https://polmind.ac.id/">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon-cerah.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <style>
        html.polmind-loading-lang body {
            visibility: hidden !important;
            opacity: 0 !important;
        }
    </style>

    <script>
        (function () {
            try {
                var savedLang = localStorage.getItem('polmind_lang') || 'id';
                if (savedLang !== 'id' && savedLang !== 'en') savedLang = 'id';
                window.polmindInitLang = savedLang;
                document.documentElement.lang = savedLang;
                if (savedLang === 'en') {
                    document.documentElement.classList.add('polmind-loading-lang');
                }
            } catch (e) {
                window.polmindInitLang = 'id';
                document.documentElement.lang = 'id';
            }
        })();
    </script>

    <script>
        (function () {
            document.documentElement.classList.remove('polmind-loading-lang');
        })();
    </script>

    <script type="module" src="{{ asset('assets/js/turbo/turbo.es2017-esm.js') }}"></script>

</head>

<body>

    {{-- Loading Page --}}
    @include('components.loading-pages')

    <!-- Header -->
    @include('pages.components.header')

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('pages.components.footer')

    <!-- FLOATING BUTTONS -->
    <div class="floating-icon">
        <button type="button" class="icon-btn" id="scrollToTop" aria-label="Scroll to top" title="Kembali ke atas">
            ↑
        </button>
        <a href="https://wa.me/6282113296897" class="icon-btn" id="whatsappBtn" target="_blank"
            rel="noopener noreferrer" aria-label="WhatsApp" title="Hubungi via WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- POPUP BERANDA -->
    @include('pages.layouts.popup_info')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/js/en/lang.js') }}" data-turbo-track="reload"></script>
    <script src="{{ asset('assets/js/popup-handler.js') }}" data-turbo-track="reload"></script>
    <script src="{{ asset('assets/js/image-modal.js') }}" data-turbo-track="reload"></script>
    <script src="{{ asset('assets/js/script.js') }}" data-turbo-track="reload"></script>
    <script src="{{ asset('assets/js/handler.js') }}" data-turbo-track="reload"></script>
    <script src="{{ asset('assets/js/animation.js') }}" data-turbo-track="reload"></script>

</body>

</html>