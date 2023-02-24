@extends('layouts.admin')

@section('title', 'Dashboard')

@section('classes', 'admin')

@section('content-header')
<x-admin.content-header :navs="[]"></x-admin.content-header>
@endsection

@section('content')

@if (auth()->user()->role->hasPermission('access-user-gift') && auth()->user()->isBuyer())
<div class="row justify-content-center py-4">
    <div class="col-md-5">
        <div class="bg-sidebar p-3 rounded rounded-4 shadow">
            <h5 class="text-uppercase text-primary font-weight-bold">Bonus Points</h5>
            <p class="mb-0 small text-muted">You will get points by purchasing items.</p>
            <p class="h2 text-muted">{{ auth()->user()->points }}</p>
        </div>
    </div>

    <div class="col-md-5">
        <div class="bg-sidebar p-3 rounded rounded-4 shadow">
            <h5 class="text-uppercase text-primary font-weight-bold">Orders</h5>
            <p class="mb-0 small text-muted">You have made many orders.</p>
            <p class="h2 text-muted">{{ auth()->user()->points }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center py-4">
    <div class="col-md-10">
        <div class="p-4 rounded rounded-4 bg-primary-light text-center">
            <h5 class="text-uppercase text-primary-dark font-weight-bold" style="letter-spacing:3px;">Gifts</h5>
            <p>We have speical plan for you. You can redeem your points with Our Special Gifts.</p>
            <a href="{{ route('admin.user-gifts.index') }}" class="btn btn-sm btn-secondary"><i class="fa fa-gift mr-2"></i> Show All</a>
        </div>
    </div>
</div>
@endif

<div class="row">

    @if(auth()->user()->role->hasPermission('access-order'))
    <div class="col-6 col-md-3 col-lg-3 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="d-block px-1 py-2 bg-sidebar shadow-sm text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-clipboard-list"></i>
            </div>
            <span class="feature-title">Today Order</span>
            <p class="text-muted h4">{{ \App\Models\Order::where('type', 'order')->todayFilter()->count() }}</p>
        </a>
    </div>
    @endif

    @if(auth()->user()->role->hasPermission('access-sale'))
    <div class="col-6 col-md-3 col-lg-3 mb-4">
        <a href="{{ route('admin.pos.index') }}?from_date={{ now()->format('Y-m-d') }}&to_date={{ now()->format('Y-m-d') }}&status=3" class="d-block px-1 py-2 bg-sidebar shadow-sm text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-receipt"></i>
            </div>
            <span class="feature-title">Today Sale</span>
            <p class="text-muted h4">{{ \App\Models\Order::saleOrder()->todayFilter()->count() }}</p>
        </a>
    </div>
    @endif

    @if(auth()->user()->role->hasPermission('access-item'))
    <div class="col-6 col-md-3 col-lg-3 mb-4">
        <a href="{{ route('admin.items.index') }}" class="d-block px-1 py-2 bg-sidebar shadow-sm text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-box-open"></i>
            </div>
            <span class="feature-title">Total Items</span>
            <p class="text-muted h4">{{ \App\Models\Item::count() }}</p>
        </a>
    </div>
    @endif

    @if(auth()->user()->role->hasPermission('access-type'))
    <div class="col-6 col-md-3 col-lg-3 mb-4">
        <a href="{{ route('admin.types.index') }}" class="d-block px-1 py-2 bg-sidebar shadow-sm text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-tint"></i>
            </div>
            <span class="feature-title">Total Categories</span>
            <p class="text-muted h4">{{ \App\Models\Type::count() }}</p>
        </a>
    </div>
    @endif
</div>

@if(auth()->user()->role->hasPermission('edit-general-information'))
<div class="row">
    <?php
    $phone = App\Models\Status::where('type', 'phone')->first();
    $general = App\Models\Status::isType('general')->first();
    $delivery = App\Models\Status::isType('delivery')->first();
    $address = App\Models\Status::isType('address')->first();
    ?>
    @if($general || $phone || $delivery || $address)
    <div class="col-md-6 mb-4">
        <div class="bg-sidebar rounded p-3 h-100">
            <div class="">
                <!-- Phone -->
                @if($phone)
                <div>
                    <h6 class="text-uppercase text-primary-dark mb-2">
                        <span>Phone</span>
                    </h6>
                    <form action="{{ route('admin.change-hotline', $phone->id) }}" class="row" method="post">
                        @csrf
                        <div class="form-group col-10">
                            <input type="text" name="phone" value="{{ $phone->name }}" class="form-control form-control-sm">
                        </div>

                        <div class="form-group col-2 align-self-end">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa fa-check"></i></button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Address -->
                @if ($address)
                <div>
                    <h6 class="text-uppercase text-primary-dark mb-2">
                        <span class="">Address</span>
                    </h6>

                    <form action="{{ route('admin.change-hotline', $address->id) }}" class="row" method="post">
                        @csrf
                        <div class="form-group col-md-10">
                            <input type="text" name="phone" value="{{ $address->name }}" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa fa-check"></i></button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Message -->
                @if($general)
                <div>
                    <h6 class="text-uppercase text-primary-dark mb-2">
                        <span class="">General Information</span>
                        <span class="small mm-font mb-0 text-secondary-dark">(Order မတင်မီ ဝယ်သူအား ပေးလိုသော အကြောင်းအရာအားထည့်ပါ။)</span>
                    </h6>


                    <form action="{{ route('admin.change-hotline', $general->id) }}" class="row" method="post">
                        @csrf
                        <div class="form-group col-md-10">
                            <input type="text" name="phone" value="{{ $general->name }}" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa fa-check"></i></button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Delivery Information -->
                @if($delivery)
                <div>
                    <h6 class="text-uppercase text-primary-dark mb-2">
                        <span class="">Delivery Information</span>
                    </h6>

                    <form action="{{ route('admin.change-hotline', $delivery->id) }}" class="row" method="post">
                        @csrf
                        <div class="form-group col-md-10">
                            <input type="text" name="phone" value="{{ $delivery->name }}" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa fa-check"></i></button>
                        </div>
                    </form>
                </div>
                @endif


            </div>
        </div>
    </div>
    @endif
    <div class="col-md-6 mb-4">
        <x-admin.dashboard.paymentype></x-admin.dashboard.paymentype>
    </div>

    <div class="col-md-6 mb-4">
        <div class="bg-sidebar rounded p-3 h-100">
            <div class="d-flex mb-4">
                <h4 class="page-title mb-0 me-2">Upload Your Logo</h4>
            </div>
            <div class="slide-box">
                <p class="mm-font">
                    အောက်ပါပုံများသည် Home Page Slides နေရာများတွင် ပြသပါမည်။ ပုံ size များတူလျှင် ပိုကောင်းပါသည်။ အကောင်းဆုံး ပုံ size မှာ
                    <span class="bg-light border text-primary">1:1 Ratio</span> ဖြစ်ပါသည်။
                </p>
                <form action="{{route('admin.upload-logo')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="profile" class="mb-3 d-block">
                            ဓာတ်ပုံပြောင်းရန်
                        </label>
                        @if(public_path('images/logo.png'))
                        <img src="{{ Storage::url('public/images/logo.png') }}" alt="{{ config('app.name') }}" class="user-profile rounded mb-3" width="100" height="100">
                        @endif
                        <div class="py-2 w-100">
                            <input type="file" name="image" id="profile">
                        </div>
                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>

</div>

@endif


@endsection
