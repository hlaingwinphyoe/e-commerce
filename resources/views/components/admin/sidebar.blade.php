<div class="app-sidebar__user d-flex align-items-center p-2 mb-2">
    <img class="app-sidebar__user-avatar" src="{{ auth()->user()->thumbnail }}" alt="User Image" style="max-height: 40px">
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

    <!-- Debts -->
    @if(auth()->user()->role->hasPermissions(['access-inventory', 'access-stock']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Inventory</span>
    </li>
    <li class="nav-item w-100 d-none">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/inventories','admin/inventories/*']) ? 'active' : '' }}" href="{{ route('admin.inventories.index') }}" title="Inventories">
            <i class="app-menu__icon fa fa-dolly-flatbed mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.purchase')}}</span>
        </a>
    </li>
    @if(auth()->user()->role->hasPermission('access-stock'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/skus','admin/skus/*']) ? 'active' : '' }}" href="{{ route('admin.skus.index') }}" title="Low Stock Skus">
            <i class="app-menu__icon fa fa-battery-quarter mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.stocks')}}</span>
        </a>
    </li>
    @endif
    @endif  

    <!-- Discounts & Coupons -->
    @if(auth()->user()->role->hasPermissions(['access-discount-type', 'access-gift', 'access-coupon', 'access-bonus-point']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.discount_setting')}}</span>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-discount-type'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/discountypes','admin/discountypes/*']) ? 'active' : '' }}" href="{{ route('admin.discountypes.index') }}" title="Discount Type">
            <i class="app-menu__icon fa fa-tags mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.discounts')}}</span>
        </a>
    </li>
    @endif

    <!-- Delivery -->
    @if(auth()->user()->role->hasPermissions(['access-delivery', 'access-region']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.delivery')}}</span>
    </li>
    @endif


    @if(auth()->user()->role->hasPermission('access-delivery'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/deliveries','admin/deliveries/*']) ? 'active' : '' }}" href="{{ route('admin.deliveries.index') }}" title="Deliveries">
            <i class="app-menu__icon fa fa-truck mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deliveries')}}</span>
        </a>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-region'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/delifees','admin/delifees/*']) ? 'active' : '' }}" href="{{ route('admin.delifees.index') }}">
            <i class="app-menu__icon fa fa-truck mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deli_fees')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/countries','admin/countries/*']) ? 'active' : '' }}" href="{{ route('admin.countries.index') }}" title="Regions">
            <i class="app-menu__icon fa fa-globe mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Countries</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/regions','admin/regions/*']) ? 'active' : '' }}" href="{{ route('admin.regions.index') }}" title="Regions">
            <i class="app-menu__icon fa fa-arrows-alt mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Regions</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/townships','admin/townships/*']) ? 'active' : '' }}" href="{{ route('admin.townships.index') }}" title="Townships">
            <i class="app-menu__icon fa fa-at mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Townships</span>
        </a>
    </li>
    @endif

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