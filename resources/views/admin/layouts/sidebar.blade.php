<nav id="sidebar">
    <div class="p-4">
        <h4 class="text-white">Admin Panel</h4>
        <hr class="bg-light">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'bg-primary' : '' }}">
                    <i class="fas fa-list"></i> Categories
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands.*') ? 'bg-primary' : '' }}">
                    <i class="fas fa-tags"></i> Brands
                </a>
            </li>
            <li>
                <a href="" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'bg-primary' : '' }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
             <li>
                <a href="" class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'bg-primary' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Coupons
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</nav> 