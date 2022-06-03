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
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">{{__('menu.website_create')}}</span>
    </li>

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-tachometer-alt mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.dashboard')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link" href="{{ route('home') }}" title="Home Page">
            <i class="app-menu__icon fa fa-home mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.home')}}</span>
        </a>
    </li>
    @if(auth()->user()->role->hasPermission('access-slide'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/slides','admin/slides/*']) || request()->is(['admin/mainfeatures','admin/mainfeatures/*']) ? 'active' : '' }}" href="{{ route('admin.slides.index') }}" title="Home Feature">
            <i class="app-menu__icon fa fa-image mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">Home Feature</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->role->hasPermission('access-faq'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/faqs','admin/faqs/*']) ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}" title="FAQs">
            <i class="app-menu__icon far fa-question-circle mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">FAQs</span>
        </a>
    </li>
    @endif

    <!-- Web Order -->
    @if(auth()->user()->role->hasPermissions(['access-order']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Web Order</span>
    </li>
    @endif
    @if(auth()->user()->role->hasPermission('access-order'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/orders','admin/orders/*']) ? 'active' : '' }}" href="{{ route('admin.orders.index') }}" title="Orders">
            <i class="app-menu__icon fa fa-arrow-down-1-9 mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.orders')}}</span>
            <?php
            $noti_count = auth()->user()->unreadNotifications()->count();
            ?>
            <span class="ms-2 badge bg-secondary rounded d-inline">{{ $noti_count }}</span>
        </a>
    </li>
    @endif

    <!-- POS -->
    @if(auth()->user()->role->hasPermissions(['access-order']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark ">{{__('menu.pos')}}</span>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-order'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/pos/create']) ? 'active' : '' }}" target="_blank" href="{{ route('admin.pos.create') }}" title="POS">
            <i class="app-menu__icon fa fa-plus mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.sales')}}</span>
        </a>
    </li>

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.pos.index') ? 'active' : '' }}" href="{{ route('admin.pos.index') }}" title="Orders">
            <i class="app-menu__icon fa fa-list-ol mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.sale_lists')}}</span>
        </a>
    </li>
    @endif

    <!-- Create -->
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">{{__('menu.create')}}</span>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}" title="Item">
            <i class="app-menu__icon fa fa-store mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{ __('menu.item') }}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.types.*') ? 'active' : '' }}" href="{{ route('admin.types.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-stream mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.category')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->RouteIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-star mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.brand')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->RouteIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}" title="Dashboard">
            <i class="app-menu__icon fa fa-users mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.suppliers')}}</span>
        </a>
    </li>

     <!-- Purchase -->
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">Purchase</span>
    </li>

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.inventories.*') ? 'active' : '' }}" href="{{ route('admin.inventories.index') }}" title="Inventory">
            <i class="app-menu__icon fa fa-basket-shopping mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{ __('menu.purchase') }}</span>
        </a>
    </li>
    <!-- Debts -->
    @if(auth()->user()->role->hasPermissions(['access-inventory', 'access-stock', 'access-return']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.inventory')}}</span>
    </li>
    <li class="nav-item w-100 d-none">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.inventories.*') ? 'active' : '' }}" href="{{ route('admin.inventories.index') }}" title="Inventories">
            <i class="app-menu__icon fa fa-dolly-flatbed mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.purchase')}}</span>
        </a>
    </li>
    @if(auth()->user()->role->hasPermission('access-stock'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.skus.*') ? 'active' : '' }}" href="{{ route('admin.skus.index') }}" title="Low Stock Skus">
            <i class="app-menu__icon fa fa-boxes-stacked mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.stocks')}}</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->role->hasPermission('access-return'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}" title="Returns">
            <i class="app-menu__icon fa fa-rotate-left mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.return')}}</span>
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

    @if(auth()->user()->role->hasPermission('access-gift'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/gifts','admin/gifts/*']) ? 'active' : '' }}" href="{{ route('admin.gifts.index') }}" title="Gifts">
            <i class="app-menu__icon fa fa-gift mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.gifts')}}</span>
        </a>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-coupon'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/coupons','admin/coupons/*']) ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}" title="Coupons">
            <i class="app-menu__icon fa fa-ticket-alt mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.coupon')}}</span>
        </a>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-bonus-points'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/bonuspoints','admin/bonuspoints/*']) ? 'active' : '' }}" href="{{ route('admin.bonuspoints.index') }}" title="Bounuspoints">
            <i class="app-menu__icon fa fa-gem mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.bonuspoints')}}</span>
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
            <i class="app-menu__icon fa fa-hand-holding-dollar mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deli_fees')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/countries','admin/countries/*']) ? 'active' : '' }}" href="{{ route('admin.countries.index') }}" title="Regions">
            <i class="app-menu__icon fa fa-globe mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.country')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/regions','admin/regions/*']) ? 'active' : '' }}" href="{{ route('admin.regions.index') }}" title="Regions">
            <i class="app-menu__icon fa fa-arrows-alt mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.region')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/townships','admin/townships/*']) ? 'active' : '' }}" href="{{ route('admin.townships.index') }}" title="Townships">
            <i class="app-menu__icon fa fa-at mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label">{{__('menu.township')}}</span>
        </a>
    </li>
    @endif

    <!-- User Control -->
    @if(auth()->user()->role->hasPermissions(['access-user', 'access-customer']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">{{__('menu.user_control')}}</span>
    </li>
    @endif

    @if(auth()->user()->role->hasPermission('access-customer'))
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->is(['admin/customers','admin/customers/*']) ? 'active' : '' }}" href="{{ route('admin.customers.index') }}" title="Customer">
            <i class="app-menu__icon fa fa-users-line mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.customer')}}</span>
        </a>
    </li>
    @endif

    <!-- Profile Setting -->
    @if(auth()->user()->role->hasPermissions(['access-role', 'access-user']))
    <li class="nav-item w-100">
        <span class="app-menu__label nav-link sidebar-label text-dark text-uppercase bg-sidebar-dark">{{__('menu.profile_setting')}}</span>
    </li>
    @endif    

    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" title="Item">
            <i class="app-menu__icon fa fa-users-gear mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.staff')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}" title="Type">
            <i class="app-menu__icon fa fa-user-shield mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.role')}}</span>
        </a>
    </li>
    <li class="nav-item w-100">
        <a class="app-menu__item d-flex align-items-center nav-link {{ request()->routeIs('admin.profiles.*') ? 'active' : '' }}" href="{{ route('admin.profiles.index') }}" title="Unit">
            <i class="app-menu__icon fa fa-user-cog mr-2"></i>
            <span class="app-menu__label ms-1 sidebar-label {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.profile')}}</span>
        </a>
    </li>


</ul>