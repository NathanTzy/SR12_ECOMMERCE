<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown">
                <a href="{{ route('dashboard') }}" class="nav-link has-dropdown"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('users.index') }}">User management</a>
                        <a class="nav-link" href="{{ route('category.index') }}">Category management</a>
                        <a class="nav-link" href="{{ route('item.index') }}">Item management</a>
                        <a class="nav-link" href="{{ route('payment.index') }}">Payment management</a>
                        <a class="nav-link" href="{{ route('discount.index') }}">Discount management</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="{{ route('dashboard') }}" class="nav-link has-dropdown"><i
                        class="fas fa-shop"></i><span>Pesanan</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('dashboard-general-dashboard') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('pesanan.index') }}">Pesanan management</a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
