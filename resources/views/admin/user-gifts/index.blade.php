@extends('layouts.admin')

@section('title', 'Gifts')

@section('classes', 'admin admin-gifts admin-gifts-index')

@section('content')
<x-admin.search-box url="{{ route('admin.user-gifts.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-2">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.gifts')}}</h4>
        <span class="text-muted form-text">( Showing {{ $gifts->count() }} records )</span>
    </div>
    <h4 class="text-primary">You have {{ number_format(auth()->user()->points) }} <small>POINT(s)</small>.</h4>
    <div class="mb-4">
        <a href="{{ route('admin.show-gifts') }}" class="btn btn-sm btn-secondary">Show my gifts</a>
    </div>
    
    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4 d-none">
        <li class="nav-item">
            <a href="{{ route('admin.user-gifts.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.user-gifts.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell-slash"></i>
                Disabled
            </a>
        </li>
    </ul>

    <div class="gift-boxes d-flex flex-wrap">
        @forelse($gifts as $gift)
        <div class="col-custom-5">
            <div class="shadow">
                <div class="box-header text-center">
                    <img src="{{ $gift->thumbnail }}" alt="{{ $gift->name }}" style="height: 250px;">
                </div>
                <div class="box-body px-3 py-2">
                    <p>{{ $gift->name }}</p>
                    <div class="text-center bg-primary rounded py-2 text-white mb-2">
                        <span>{{ number_format($gift->points) }} <small>POINTS</small></span>
                    </div>
                    @if(auth()->user()->points > $gift->points)
                    <form action="{{ route('admin.user-gifts.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="gift" value="{{ $gift->id }}">
                        <button type="submit" class="w-100 btn btn-outline-danger">လက်ဆောင်ရယူရန်</button>
                    </form>                    
                    @else
                    <button class="w-100 btn btn-outline-secondary">Points မပြည့်သေးပါ</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <h2 class="w-100 text-center">There is no gifts still yet.</h2>
        @endforelse
    </div>

</div>

@endsection
