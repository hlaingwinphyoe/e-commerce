<!-- Toggle Menu -->
<div class="app-header-left px-3 d-flex align-items-center">
    <a href="#" class="text-white" data-bs-toggle="sidebar"><i class="fa fa-bars fa-lg"></i></a>
    <div class="app-header-left-label text-center w-100">
        <span class="text-white text-uppercase ms-3">Menu</span>
    </div>
</div>



<ul class="nav app-nav d-flex align-items-center justify-content-end w-100 mb-0">

    <li class="dropdown d-flex align-items-center me-3">
        <a class="app-nav__item dropdown-toggle text-decoration-none text-white" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu">
            @if (App::getLocale() == 'mm')
                <img src="{{asset('images/lang/myanmar.png')}}" alt="myanmar"> 
            @else
                <img src="{{asset('images/lang/english.png')}}" alt="english"> 
            @endif
    
            &nbsp; <span class="font-weight-bold ">{{App::getLocale() == 'mm' ? 'Myanmar' : 'English'}}</span></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right navbar-menu">
            <li><a class="dropdown-item" href="{{ route('admin.langs.switch', 'en') }}"><img src="{{asset('images/lang/english.png')}}" alt="english"> <span class="ml-2">English</span></a></li>
            <li><a class="dropdown-item " href="{{ route('admin.langs.switch', 'mm') }}"><img src="{{asset('images/lang/myanmar.png')}}" alt="myanmar"> <span class="ml-2">Myanmar</span></a></li>
        </ul>
    </li>

    <?php
    $notifications = auth()->user()->unreadNotifications()->latest()->get();
    $noti_count = auth()->user()->unreadNotifications()->count();
    ?>
    <notification-list :user_id="{{ auth()->user()->id }}"></notification-list>

    <li class="dropdown">
        <a class="app-nav__item dropdown-toggle p-1 text-white" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu">
            <i class="fa fa-user"></i>
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