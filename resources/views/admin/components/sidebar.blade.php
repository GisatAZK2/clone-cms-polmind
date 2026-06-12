@php
    $user = auth()->user();

    $role = strtolower(trim((string) ($user->role ?? '')));

    $isSuperAdmin = auth()->check() && $role === 'superadmin';
@endphp

<aside class="sidebar" id="sidebar">

    {{-- ── Logo / Brand ── --}}
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="{{ asset('assets/images/favicon-cerah.ico') }}" alt="Logo Polmind" class="sidebar-logo-img preview-image">
            </div>
            <div class="sidebar-logo-text">
                <h3>CMS Polmind</h3>
                <small>Admin Dashboard</small>
            </div>
        </div>
    </div>

    {{-- ── Navigasi Utama ── --}}
    <nav>

        {{-- SEARCH MENU --}}
        <div class="sidebar-search">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="menuSearch" class="menu-search-input"
                    placeholder="Cari menu..."
                    data-translate="sidebar.search"
                    aria-label="Cari menu navigasi" autocomplete="off">
                <button type="button" class="search-clear" id="searchClear" style="display: none;">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
            <div class="search-results" id="searchResults" style="display: none;"></div>
        </div>

        {{-- GROUP: Umum --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupUmum">Umum</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" title="Dashboard">
                        <span class="nav-icon-wrap"><i class="bi bi-speedometer2"></i></span>
                        <span class="nav-label" data-translate="sidebar.dashboard">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Beranda / Halaman Utama (GABUNGAN) --}}
        <div class="nav-section">
           
            <span class="nav-group-label" data-translate="sidebar.groupBeranda">Beranda</span>
            <ul class="nav-menu">
                 @if($isSuperAdmin)
                <li class="{{ request()->routeIs('admin.headline.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.headline.index') }}" title="Headline">
                        <span class="nav-icon-wrap"><i class="bi bi-megaphone"></i></span>
                        <span class="nav-label" data-translate="sidebar.headline">Headline</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.content-pendaftaran.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.content-pendaftaran.index') }}" title="Content Pendaftaran">
                        <span class="nav-icon-wrap"><i class="bi bi-card-list"></i></span>
                        <span class="nav-label" data-translate="sidebar.contentPendaftaran">Content Pendaftaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.home-stat.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.home-stat.index') }}" title="Statistik Beranda">
                        <span class="nav-icon-wrap"><i class="bi bi-bar-chart-line"></i></span>
                        <span class="nav-label" data-translate="sidebar.stats">Statistik</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.keunggulan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.keunggulan.index') }}" title="Keunggulan">
                        <span class="nav-icon-wrap"><i class="bi bi-star"></i></span>
                        <span class="nav-label" data-translate="sidebar.keunggulan">Keunggulan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.karakter.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.karakter.index') }}" title="Karakter">
                        <span class="nav-icon-wrap"><i class="bi bi-hand-thumbs-up"></i></span>
                        <span class="nav-label" data-translate="sidebar.karakter">Karakter</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.projects.index') }}" title="Projects">
                        <span class="nav-icon-wrap"><i class="bi bi-folder2-open"></i></span>
                        <span class="nav-label" data-translate="sidebar.projects">Projects</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.prodi.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.prodi.index') }}" title="Prodi">
                        <span class="nav-icon-wrap"><i class="bi bi-mortarboard"></i></span>
                        <span class="nav-label" data-translate="sidebar.prodi">Prodi</span>
                    </a>
                </li>
                @endif
                <li class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.news.index') }}" title="Berita">
                        <span class="nav-icon-wrap"><i class="bi bi-newspaper"></i></span>
                        <span class="nav-label" data-translate="sidebar.berita">Berita</span>
                    </a>
                </li>
                @if($isSuperAdmin)
                <li class="{{ request()->routeIs('admin.mitra.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.mitra.index') }}" title="Mitra">
                        <span class="nav-icon-wrap"><i class="bi bi-building"></i></span>
                        <span class="nav-label" data-translate="sidebar.mitra">Mitra</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.sambutan-direktur.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sambutan-direktur.index') }}" title="Sambutan Direktur">
                        <span class="nav-icon-wrap"><i class="bi bi-person-badge"></i></span>
                        <span class="nav-label" data-translate="sidebar.sambutanDirektur">Sambutan Direktur</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.popup.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.popup.index') }}" title="Popup">
                        <span class="nav-icon-wrap"><i class="bi bi-window-stack"></i></span>
                        <span class="nav-label" data-translate="sidebar.popup">Popup</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Halaman Profil --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupHalamanProfil">Halaman Profil</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dosen.index') }}" title="Dosen">
                        <span class="nav-icon-wrap"><i class="bi bi-person-workspace"></i></span>
                        <span class="nav-label" data-translate="sidebar.dosen">Dosen</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Keunikan dan Keunggulan --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupKeunikanDanKeunggulan">Keunikan & Keunggulan</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.keunikan-dan-keunggulan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.keunikan-dan-keunggulan.index') }}" title="Keunikan dan Keunggulan">
                        <span class="nav-icon-wrap"><i class="bi bi-award"></i></span>
                        <span class="nav-label" data-translate="sidebar.keunikanDanKeunggulan">Keunikan & Keunggulan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Dokumentasi --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupDokumentasi">Dokumentasi</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.dokumentasi.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dokumentasi.index') }}" title="Dokumentasi">
                        <span class="nav-icon-wrap"><i class="bi bi-camera-video"></i></span>
                        <span class="nav-label" data-translate="sidebar.dokumentasi">Dokumentasi</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Sarjana Terapan --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupProgramSarjanaTerapan">Sarjana Terapan</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.program-sarjana-terapan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.program_sarjana_terapan.index') }}" title="Program Sarjana Terapan">
                        <span class="nav-icon-wrap"><i class="bi bi-book-half"></i></span>
                        <span class="nav-label" data-translate="sidebar.programSarjanaTerapan">Program Sarjana Terapan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Penerimaan Mahasiswa Baru --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupPendaftaranMahasiswaBaru">Penerimaan Mahasiswa Baru</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.pendaftaran-mahasiswa-baru.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pendaftaran-mahasiswa-baru.index') }}" title="Pendaftaran Mahasiswa Baru">
                        <span class="nav-icon-wrap"><i class="bi bi-person-plus"></i></span>
                        <span class="nav-label" data-translate="sidebar.pendaftaranMahasiswaBaru">Pendaftaran Mahasiswa Baru</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>

        {{-- GROUP: Management User --}}
        <div class="nav-section">
            <span class="nav-group-label" data-translate="sidebar.groupManagementUser">Management User</span>
            <ul class="nav-menu">
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" title="Management User">
                        <span class="nav-icon-wrap"><i class="bi bi-person-gear"></i></span>
                        <span class="nav-label" data-translate="sidebar.managementUser">Management User</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-divider"></div>
        @endif

    </nav>

    {{-- ── Logout (pinned bottom) ── --}}
    <div class="sidebar-bottom">
        <ul class="nav-menu">
            <li>
                <a href="#" title="Logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="nav-icon-wrap"><i class="bi bi-box-arrow-left"></i></span>
                    <span class="nav-label" data-translate="sidebar.logout">Logout</span>
                </a>
            </li>
        </ul>
    </div>

</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>