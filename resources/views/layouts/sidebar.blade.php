<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.webp') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">NES</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{ route('home') }}" class="nav-link {{ Route::currentRouteName() === 'home' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ Route::currentRouteName() === 'users.index' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('company.index') }}" class="nav-link {{ Route::currentRouteName() === 'company.index' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Companies</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('service.index') }}" class="nav-link {{ Route::currentRouteName() === 'service.index' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Services</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('product.index') }}" class="nav-link {{ Route::currentRouteName() === 'product.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mechanic.index') }}" class="nav-link {{ Route::currentRouteName() === 'mechanic.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tools"></i>
                        <p>Mechanic</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendor.index') }}" class="nav-link {{ Route::currentRouteName() === 'vendor.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-industry"></i>
                        <p>Vendor</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('order.index') }}" class="nav-link {{ Route::currentRouteName() === 'order.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Order Management</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('purchase.index') }}" class="nav-link {{ Route::currentRouteName() === 'purchase.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>Vendor Purchases</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
