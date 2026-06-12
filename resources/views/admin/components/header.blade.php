<header class="topbar">

    {{-- Kiri: toggle sidebar + breadcrumb/judul --}}
    <div class="d-flex align-items-center gap-3" style="min-width:0; flex:1;">
        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="topbar-breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="topbar-breadcrumb-home">
                <i class="bi bi-house-door"></i>
            </a>
            <span class="topbar-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
            <div class="topbar-title">
                @yield('page-title', 'Dashboard')
            </div>
        </div>
    </div>

    {{-- Kanan: actions + user --}}
    <div class="topbar-right">

        {{-- Notifications (opsional, bisa dihapus jika tidak dipakai) --}}
        {{-- 
        <button class="topbar-icon-btn" title="Notifikasi">
            <i class="bi bi-bell"></i>
            <span class="topbar-badge">3</span>
        </button>
        --}}

        <div class="topbar-divider"></div>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <button class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="topbar-user-info">
                    <span class="topbar-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="topbar-user-role">{{ auth()->user()->email ?? '' }}</span>
                </div>
                <i class="bi bi-chevron-down topbar-user-chevron"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <span class="dropdown-header">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="bi bi-person-circle"></i> <span data-translate="header.profile">Profil Saya</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.setting') }}">
                        <i class="bi bi-gear"></i> <span data-translate="header.settings">Pengaturan</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item dropdown-item--danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i> <span data-translate="header.logout">Logout</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>
