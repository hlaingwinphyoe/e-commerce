<div class="app-sidebar__user d-flex align-items-center p-2 mb-2">
    <span><i class="fa fa-user"></i></span>
    <div class="ms-3 app-sidebar__user-label">
        <p class="app-sidebar__user-name mb-0 text-uppercase">{{ auth()->check() ? auth()->user()->name : '' }}</p>
        <p class="app-sidebar__user-designation mb-0 text-primary small">{{ auth()->check() && auth()->user()->role ? auth()->user()->role->name : '' }}</p>
    </div>
</div>
<ul class="app-menu nav">
    <!-- General -->
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">Website Create</span>
    </li>

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-tachometer-alt mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Dashboard</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link" href="{{ route('home') }}" title="Home Page">
            <i class="app-menu__icon fa fa-home mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.home')}}</span>
        </a>
    </li>
    <!-- Create -->
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">Create</span>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['/admin/items/', '/admin/items/*']) ? 'active' : '' }}" href="{{ route('admin.items.index') }}" title="Item">
            <i class="app-menu__icon fa fa-stream mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{ __('menu.item') }}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.types.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-stream mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Category</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-star mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Brand</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-users mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Suppliers</span>
        </a>
    </li>

    <!-- User Control -->
    @if(auth()->user()->role->hasPermissions(['access-user', 'access-customer']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">User Control</span>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-customer'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/customers','admin/customers/*']) ? 'active' : '' }}" href="{{ route('admin.customers.index') }}" title="Customer">
            <i class="app-menu__icon fa fa-user mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.customer')}}</span>
        </a>
    </li>
    @endif

    <!-- Profile Setting -->
    @if(auth()->user()->role->hasPermissions(['access-role', 'access-user']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">Profile Setting</span>
    </li>
    @endif    

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin/staffs/*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" title="Item">
            <i class="app-menu__icon fa fa-user-shield mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Staff</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin/roles/*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}" title="Type">
            <i class="app-menu__icon fa fa-circle-notch mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Role</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin/profiles/*') ? 'active' : '' }}" href="{{ route('admin.profiles.index') }}" title="Unit">
            <i class="app-menu__icon fa fa-user-cog mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Profile</span>
        </a>
    </li>


</ul>