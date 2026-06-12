<!-- Header -->
<header>
  {{-- No hide --}}
  <div class="topbar-fixed">
    <div class="topbar">
      <div class="topbar__container">
        <div class="topbar__left">
          <a class="topbar__item" href="mailto:info@polmind.ac.id" aria-label="Email Polmind">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2"></rect>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </svg>
            <span>info@polmind.ac.id</span>
          </a>
          <span class="topbar__sep">|</span>
          <a class="topbar__item" href="tel:+628211151337" aria-label="Telepon Polmind">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
              </path>
            </svg>
            <span>+62 821 1151 3337</span>
          </a>
        </div>
        <div class="topbar__right">
          <div class="language-selector">
            <button class="lang-selected" id="langSelected">
              <img src="{{ asset('assets/flags/id.svg') }}" alt="Indonesia">
              <span style="color:rgba(0,0,0)">Bahasa</span>
            </button>

            <div class="lang-dropdown" id="langDropdown">
              <div class="lang-option" data-lang="id">
                <img src="{{ asset('assets/flags/id.svg') }}" alt="Indonesia">
                <span style="color:rgba(0,0,0)">Bahasa</span>
              </div>

              <div class="lang-option" data-lang="en">
                <img src="{{ asset('assets/flags/en.svg') }}" alt="English">
                <span style="color:rgba(0,0,0)">English</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="header-wrapper">
    <div class="header-main">
      <div class="header-container">

        <!-- Logo -->
        <div class="logo-section">
          <a href="/beranda" class="logo-link">
            <img src="/assets/images/logo-white.png" alt="POLITEKNIK MITRA INDUSTRI" class="logo-img preview-image"
              data-modal-skip="true">
          </a>
        </div>

        <!-- Burger Mobile -->
        <div class="burger" id="burgerMenu">
          <span></span>
          <span></span>
          <span></span>
        </div>

        <!-- Desktop Nav -->
        <nav class="desktop-nav">
          <ul class="nav-menu">
            <li><a href="/beranda" data-translate="home">BERANDA</a></li>

            <li class="nav-dropdown">
              <div class="nav-dropdown-header">
                <a href="/profil" data-translate="profile">PROFIL</a>
                <button class="dropdown-toggle" aria-label="Toggle dropdown menu" aria-expanded="false">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                  </svg>
                </button>
              </div>
              <ul class="dropdown-menu">
                <li><a href="/daftar_dosen" data-translate="lecturers">DAFTAR DOSEN</a></li>
                <li><a href="/daftar_tendik" data-translate="staff">DAFTAR TENDIK</a></li>
              </ul>
            </li>

            <li><a href="/keunikan" data-translate="advantages">KEUNIKAN DAN KEUNGGULAN</a></li>
            <li><a href="/dokumentasi" data-translate="documentation">DOKUMENTASI</a></li>
            <li><a href="/prodi" data-translate="applied-program">PRODI SARJANA TERAPAN</a></li>
            <li><a href="/pmb" data-translate="admission">PENERIMAAN MAHASISWA BARU</a></li>
            <li class="search-icon">
              <button id="search-icon" class="search-btn" aria-label="Open search modal">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.35-4.35"></path>
                </svg>
              </button>
            </li>
          </ul>
        </nav>


      </div>

      <!-- Mobile Nav -->
      <ul class="mobile-nav" id="mobileNav">
        <li class="mobile-topbar-item">
          <div class="topbar__left topbar__left--mobile">
            <a class="topbar__item" href="mailto:info@polmind.ac.id" aria-label="Email Polmind">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
              </svg>
              <span>info@polmind.ac.id</span>
            </a>
            <span class="topbar__sep">|</span>
            <a class="topbar__item" href="tel:+628211151337" aria-label="Telepon Polmind">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                </path>
              </svg>
              <span>+62 821 1151 3337</span>
            </a>
            
            <div class="language-selector mobile-language">
              <button class="lang-selected" id="langSelectedMobile">
                <img src="{{ asset('assets/flags/id.svg') }}" alt="Indonesia">
                <span>Bahasa</span>
              </button>

              <div class="lang-dropdown" id="langDropdownMobile">
                <div class="lang-option" data-lang="id">
                  <img src="{{ asset('/assets/flags/id.svg') }}" alt="Indonesia">
                  <span style="color: rgba(0,0,0)">Bahasa</span>
                </div>

                <div class="lang-option" data-lang="en">
                  <img src="{{ asset('/assets/flags/en.svg') }}" alt="English">
                  <span style="color: rgba(0,0,0)">English</span>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li>
          <a href="/beranda" data-translate="home">BERANDA</a>
        </li>

        <li class="nav-dropdown">
          <div class="nav-dropdown-header">
            <a href="/profil" data-translate="profile">PROFIL</a>
            <button class="dropdown-toggle" aria-label="Toggle dropdown menu" aria-expanded="false">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>
          </div>

          <ul class="dropdown-menu">
            <li>
              <a href="/daftar_dosen" data-translate="lecturers">DAFTAR DOSEN</a>
            </li>
            <li>
              <a href="/daftar_tendik" data-translate="staff">DAFTAR TENDIK</a>
            </li>
          </ul>
        </li>

        <li>
          <a href="/keunikan" data-translate="advantages">KEUNIKAN DAN KEUNGGULAN</a>
        </li>

        <li>
          <a href="/dokumentasi" data-translate="documentation">DOKUMENTASI</a>
        </li>

        <li>
          <a href="/prodi" data-translate="applied-program">PRODI SARJANA TERAPAN</a>
        </li>

        <li>
          <a href="/pmb" data-translate="admission">PENERIMAAN MAHASISWA BARU</a>
        </li>
        <li class="mobile-search">
          <button id="mobile-search-btn" class="mobile-search-btn" aria-label="Open search modal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.35-4.35"></path>
            </svg>
            Cari
          </button>
        </li>
      </ul>

    </div>

    <!-- Search Dropdown -->
    <div class="search-dropdown" id="searchDropdown">
      <form action="/search" method="GET" class="search-form" autocomplete="off">
        <input id="searchInput" type="text" name="query" class="dropdown-search-input"
          placeholder="Cari section atau halaman..." aria-label="Search query" autocomplete="off">
        <button type="submit" class="search-submit" aria-label="Submit search">Cari</button>
      </form>
      <div id="searchResults" class="search-results"></div>
    </div>

  </div>
<!-- Anti flash khusus value language selector -->
<style>
  .language-selector .lang-selected > img,
  .language-selector .lang-selected > span {
    visibility: hidden;
  }

  html.polmind-lang-selector-ready .language-selector .lang-selected > img,
  html.polmind-lang-selector-ready .language-selector .lang-selected > span {
    visibility: visible;
  }
</style>

<!-- Sync Language Selector Values -->
<script>
  (function () {
    const LANG_KEY = 'polmind_lang';

    function normalizeLang(lang) {
      return lang === 'en' ? 'en' : 'id';
    }

    function getSavedLang() {
      try {
        return normalizeLang(
          localStorage.getItem(LANG_KEY) ||
          window.polmindInitLang ||
          document.documentElement.getAttribute('lang') ||
          'id'
        );
      } catch (e) {
        return 'id';
      }
    }

    function syncLanguageSelectors(lang) {
      lang = normalizeLang(lang);

      document.querySelectorAll('.language-selector').forEach(selector => {
        const selected = selector.querySelector('.lang-selected');
        const activeOption = selector.querySelector(`.lang-option[data-lang="${lang}"]`);

        if (!selected || !activeOption) return;

        selected.innerHTML = activeOption.innerHTML;
        selected.setAttribute('data-current-lang', lang);
        selected.setAttribute('type', 'button');
      });

      document.documentElement.classList.add('polmind-lang-selector-ready');
    }

    function applyLanguage(lang) {
  lang = normalizeLang(lang);

  const currentLang = getSavedLang();

  try {
    localStorage.setItem(LANG_KEY, lang);
  } catch (e) {}

  document.documentElement.lang = lang;

  syncLanguageSelectors(lang);

  document.querySelectorAll('.lang-dropdown').forEach(menu => {
    menu.classList.remove('show');
  });

  // Reload hanya kalau bahasa benar-benar berubah
  if (currentLang !== lang) {
    window.location.reload();
  }
}
    function bindLanguageSelectors() {
      const lang = getSavedLang();

      syncLanguageSelectors(lang);

      document.querySelectorAll('.language-selector').forEach(selector => {
        if (selector.dataset.langBound === 'true') return;
        selector.dataset.langBound = 'true';

        const selected = selector.querySelector('.lang-selected');
        const dropdown = selector.querySelector('.lang-dropdown');

        if (!selected || !dropdown) return;

        selected.setAttribute('type', 'button');

        selected.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();

          document.querySelectorAll('.lang-dropdown').forEach(menu => {
            if (menu !== dropdown) menu.classList.remove('show');
          });

          dropdown.classList.toggle('show');
        });

        selector.querySelectorAll('.lang-option').forEach(option => {
          option.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const nextLang = normalizeLang(this.dataset.lang);

            applyLanguage(nextLang);

            document.querySelectorAll('.lang-dropdown').forEach(menu => {
              menu.classList.remove('show');
            });
          });
        });
      });
    }

    document.addEventListener('click', function () {
      document.querySelectorAll('.lang-dropdown').forEach(menu => {
        menu.classList.remove('show');
      });
    });

    document.addEventListener('DOMContentLoaded', bindLanguageSelectors);
    document.addEventListener('turbo:load', bindLanguageSelectors);
    document.addEventListener('turbo:render', bindLanguageSelectors);

    bindLanguageSelectors();
  })();
</script>
</header>