<!-- Toggle Menu -->
<div class="app-header-left px-3 d-flex align-items-center">
    <a href="#" class="text-white" data-bs-toggle="sidebar"><i class="fa fa-bars fa-lg"></i></a>
    <div class="app-header-left-label text-center w-100">
        <span class="text-white text-uppercase ms-3">Menu</span>
    </div>
</div>



<ul class="nav app-nav px-3 d-flex align-items-center justify-content-end w-100 mb-0">
    <?php
    $notifications = auth()->user()->unreadNotifications()->latest()->get();
    $noti_count = auth()->user()->unreadNotifications()->count();
    ?>
    <notification-list :user_id="{{ auth()->user()->id }}"></notification-list>

    <li class="dropdown">
        <a class="app-nav__item p-1 text-white dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu">
            <img class="header-profile" src="{{ auth()->check() ? auth()->user()->getImage() : '' }}">
        </a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right navbar-menu shadow py-0">
            <li>
                <a class="dropdown-item" href="{{ route('admin.profiles.index') }}">
                    <i class="fa fa-user"></i>
                    <span class="ms-2"> Profile</span>
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="post" class="" id="logout">
                    @csrf
                    @method('post')
                </form>
                <a href="#" class="dropdown-item" onClick="event.preventDefault();document.getElementById('logout').submit();">
                    <small class="me-1"><i class="fa fa-power-off"></i></small>
                    <span class="">Log Out</span>
                </a>
            </li>
        </ul>
    </li>
</ul>
