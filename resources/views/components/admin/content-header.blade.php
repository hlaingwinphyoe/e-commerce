<nav aria-label="breadcrumb" class="w-100 px-3 py-2 border rounded mb-3 bg-sidebar">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>

        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                Dashboard
            </a>
        </li>

        @foreach($navs as $ind => $nav)
        <li class="breadcrumb-item {{  $ind == count($navs)-1 ? 'active' : '' }}">
            @if($ind !== count($navs)-1)
            <a href="{{ route('admin.'. $nav .'.index') }}" class="text-capitalize breadcrumb-link">
                {{ $nav }}
            </a>
            @else
            <span class="text-capitalize">{{ $nav }}</span>
            @endif
        </li>
        @endforeach

    </ol>
</nav>
