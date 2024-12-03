<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @php
        $activePaths = [
            'editor/menu',
            'editor/user',
            'editor/uom',
            'editor/sku-type',
            'editor/warehouse',
            'editor/customer',
            'editor/category'
        ];
        
    @endphp
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ collect($activePaths)->contains(fn($path) => Request::is($path)) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-database"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseUtilities" class="collapse {{ collect($activePaths)->contains(fn($path) => Request::is($path)) ? 'show' : '' }}" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if (Auth::user()->hasPermissionByName('User','read'))
                <a class="collapse-item {{ Request::is('editor/user') ? 'active' : '' }}" href="{{ route('editor.user') }}">User</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Menu','read'))
                <a class="collapse-item {{ Request::is('editor/menu') ? 'active' : '' }}" href="{{ route('editor.menu') }}">Menu</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Uom','read'))
                <a class="collapse-item {{ Request::is('editor/uom') ? 'active' : '' }}" href="{{ route('editor.uom') }}">Uom</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Category','read'))
                <a class="collapse-item {{ Request::is('editor/category') ? 'active' : '' }}" href="{{ route('editor.category') }}">Category</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Sku Type','read'))
                <a class="collapse-item {{ Request::is('editor/sku-type') ? 'active' : '' }}" href="{{ route('editor.sku-type') }}">SKU Type</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Warehouse','read'))
                <a class="collapse-item {{ Request::is('editor/warehouse') ? 'active' : '' }}" href="{{ route('editor.warehouse') }}">Warehouse</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Vendor','read'))
                <a class="collapse-item {{ Request::is('editor/vendor') ? 'active' : '' }}" href="{{ route('editor.vendor') }}">Vendor</a>
                @endif
                @if (Auth::user()->hasPermissionByName('Customer','read'))
                <a class="collapse-item {{ Request::is('editor/customer') ? 'active' : '' }}" href="{{ route('editor.customer') }}">Customer</a>
                @endif
            </div>
        </div>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>