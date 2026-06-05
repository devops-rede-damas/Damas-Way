<header class="top-header">
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <div class="header-right">
        @auth
            <a href="#" class="user-profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nome, 0, 1)) }}
                </div>
                <div class="user-info d-none d-md-block">
                    <p class="user-name">{{ Auth::user()->nome }}</p>
                    <p class="user-role">{{ Auth::user()->nivel->nivel ?? '' }}</p>
                </div>
                <i class="bi bi-chevron-down ms-1" style="font-size: 0.65rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('perfil') }}">
                        <i class="bi bi-person-circle me-2"></i> Meu Perfil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Sair
                    </a>
                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</header>
