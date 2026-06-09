<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('img/logo_damas_way_dark.png') }}" alt="Damas Way" class="sidebar-logo" style="height: 36px;">
            <h4 class="sidebar-brand-text">Damas<span>Way</span></h4>
        </a>
    </div>

    @auth
    @php $nivelNome = mb_strtolower(auth()->user()->nivel->nivel ?? ''); @endphp
    <ul class="sidebar-nav">
        <li class="nav-label">Menu Principal</li>

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </li>

        @if(in_array($nivelNome, ['super administrador', 'administrador']))
        <li class="nav-label">Pedidos</li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Pedidos
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->routeIs('ocorrencias.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Ocorrências
            </a>
        </li>

        <li class="nav-label">Catálogo</li>
        <li class="nav-item">
            <a href="{{ route('produtos.index') }}" class="nav-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> Produtos
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categorias
            </a>
        </li>

        <li class="nav-label">Configurações</li>
        <li class="nav-item">
            <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Usuários
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transportadoras.index') }}" class="nav-link {{ request()->routeIs('transportadoras.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Transportadoras
            </a>
        </li>

        @if($nivelNome === 'super administrador')
        <li class="nav-item">
            <a href="{{ route('status-pedido.index') }}" class="nav-link {{ request()->routeIs('status-pedido.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Status de Pedido
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('coligadas.index') }}" class="nav-link {{ request()->routeIs('coligadas.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Coligadas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('filiais.index') }}" class="nav-link {{ request()->routeIs('filiais.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> Filiais
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('niveis.index') }}" class="nav-link {{ request()->routeIs('niveis.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Níveis
            </a>
        </li>
        @endif
        @endif
    </ul>

    <div class="sidebar-footer">
        <a href="{{ route('perfil') }}" class="btn btn-outline-light btn-sm w-100 mb-2">
            <i class="bi bi-person-circle me-1"></i> Meu Perfil
        </a>
        <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm w-100"
            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="bi bi-box-arrow-left me-1"></i> Sair
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    @endauth
</nav>
