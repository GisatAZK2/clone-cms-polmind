

<script>
    (function () {
        var isTurboNavigation = document.documentElement.hasAttribute('data-turbo-visit-direction')
            || (window.performance && window.performance.getEntriesByType
                && window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward');

        function hideLoader() {
            var loader = document.getElementById('pageLoader');
            if (!loader) return;
            loader.style.transition = 'opacity 0.4s ease';
            loader.style.opacity = '0';
            setTimeout(function () {
                if (loader.parentNode) loader.parentNode.removeChild(loader);
            }, 400);
        }

        if (isTurboNavigation) {
            document.addEventListener('DOMContentLoaded', function () {
                var loader = document.getElementById('pageLoader');
                if (loader) loader.style.display = 'none';
            });
            return;
        }
        if (document.readyState === 'complete') {
            setTimeout(hideLoader, 500);
        } else {
            window.addEventListener('load', function () {
                setTimeout(hideLoader, 500);
            });
            setTimeout(hideLoader, 3000);
        }
    })();
</script>
<script>
    document.addEventListener('turbo:visit', function () {
        var loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });

    document.addEventListener('turbo:render', function () {
        var loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });

    document.addEventListener('turbo:load', function () {
        var loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });
</script>



<div id="pageLoader">
    <div class="loader-inner">
        <img src="{{ asset('assets/images/favicon-cerah.ico') }}" alt="Polmind" class="loader-logo">
        <div class="loader-bar-wrap">
            <div class="loader-bar"></div>
        </div>
        <p class="loader-text" data-translate="loader-text">Memuat halaman...</p>
    </div>
</div>