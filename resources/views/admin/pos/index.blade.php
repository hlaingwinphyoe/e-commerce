@extends('layouts.admin')

@section('title', 'Sale Lists')

@section('classes', 'admin admin-pos admin-pos-index')

@section('content')

{{-- <x-admin.search-box url="{{ route('admin.pos.index') }}"></x-admin.search-box> --}}
<div class="row mb-3">
    <form action="{{ route('admin.pos.index') }}" class="col-md-8 col-10 d-flex align-items-center px-2">
        <div class="input-group mb-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search with code or name" value="{{ request('q') }}">
            <div class="input-group-text bg-secondary">
                <button type="submit" class="p-0 border-0 bg-transparent">
                    <small class="text-white"><i class="fa fa-search"></i></small>
                </button>
            </div>
        </div>
    </form>
    <div class="col-2 d-desktop-none">
        <a class="btn btn-primary" data-toggle="filter-toggler" href="#">
            <small><i class="fa fa-filter"></i></small>
        </a>
    </div>
</div>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.sale_lists')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $orders->count() }}</span> of total <span
            class="">{{ $orders->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-order'))
            <div class="me-2 mb-1">
                <a href="{{ route('admin.pos.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <div class="filter-content">
                <form action="{{ route('admin.pos.index') }}" class="d-flex flex-wrap">
                    <div class="form-group me-2">
                        <select name="delivery" class="form-select">
                            <option value="">Select Delivery</option>
                            @foreach($deliveries as $delivery)
                            <option value="{{ $delivery->id }}" {{ request()->delivery == $delivery->id ? 'selected' : '' }}>
                                {{ $delivery->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group me-2">
                        <input type="date" name="from_date" class="form-control form-control-sm"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group me-2">
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-dark me-2">Filter</button>
                        <a href="{{ route('admin.pos.index') }}" class="btn btn-sm btn-primary">
                            <small><i class="fa fa-redo"></i></small>
                        </a>
                    </div>
                </form>
            </div>

            <div class="d-mobile-none">
                <form action="{{ route('admin.pos.index') }}" class="d-flex responsive-flex">
                    <div class="form-group me-2">
                        <select name="delivery" class="form-select">
                            <option value="">Select Delivery</option>
                            @foreach($deliveries as $delivery)
                            <option value="{{ $delivery->id }}" {{ request()->delivery == $delivery->id ? 'selected' : '' }}>
                                {{ $delivery->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group me-2">
                        <input type="date" name="from_date" class="form-control form-control-sm"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group me-2">
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-dark me-2">Filter</button>
                        <a href="{{ route('admin.pos.index') }}" class="btn btn-sm btn-primary">
                            <small><i class="fa fa-redo"></i></small>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    $query = '?';
    $query .= request('status') ? 'status=' . request('status') : '';
    if (request('from_date')) {
        $query .= request('status') ? '&from_date=' . request('from_date') : '?from_date=' . request('from_date');
    }
    if (request('to_date')) {
        $query .= request('status') || request('from_date') ? '&to_date=' . request('to_date') : '?to_date=' . request('to_date');
    }
    if (request('sku')) {
        $query .= request('status') || request('from_date') || request('to_date') ? '&sku=' . request('sku') : '?sku=' . request('sku');
    }
    ?>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Order No.</th>
                    <th>သင့်ငွေ</th>
                    <th>ငွေရှင်း</th>
                    <th>Delivery</th>
                    <th>Date</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <?php $total = $order->return() ? $order->getSubTotal() - $order->return()->price : $order->getSubTotal(); ?>
                <tr id="tr-{{ $order->id }}">
                    <td>
                        <p class="mb-0">{{ $order->order_no }}</p>
                        @if($order->customer)
                        <small class="mb-0 text-primary">{{ $order->customer->name }}</small>
                        @endif
                    </td>
                    <td>
                        {{ number_format($order->getSubTotal()) }}
                    </td>
                    <td>
                        <?php $balance = $order->getBalance() + $order->getChange(); ?>
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() == $total &&
                        abs($order->getBalance()) == 0)
                        <span class="text-success">Paid</span>
                        @else
                        <span class="text-danger me-1">{{ number_format($order->getPayAmount() - $order->getReturnAmount() - $order->getChange()  == $total && abs($order->getBalance()) == 0 ? 0 : $order->getBalance() + $order->getChange()) }}</span>
                        
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() != $total && abs($order->getBalance()) != 0 && $order->status->slug != 'cancel')
                        <a href="#payment-modal-{{ $order->id }}" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="modal">
                            <small>Pay</small>
                        </a>
                        @include('admin.pos.payment')
                        @endif

                        @endif
                    </td>
                    <td width="200">
                        <form action="{{ route('admin.update-order-delivery', $order->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <select name="delivery" id="role-select-{{ $order->id }}"
                                    class="role-select form-select">
                                    <option value="0" selected disabled>Choose Delivery</option>
                                    @foreach($deliveries as $delivery)

                                    <?php
                                    if ($order->deliveries->count()) {
                                        $order_delivery = $order->deliveries->first()->id;
                                    } else {
                                        $order_delivery = 0;
                                    }

                                    ?>

                                    <option value="{{ $delivery->id }}"
                                        {{$order_delivery == $delivery->id ? 'selected' : ''}}>{{ $delivery->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </td>
                    <td>
                        <small>{{ $order->updated_at ? $order->updated_at->format('m/d/Y') : $order->created_at->format('m/d/Y') }}</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-order') && $order->status->slug != 'cancel')
                        <a href="{{ route('admin.pos.create') }}?order_no={{ $order->order_no ?? $order->id }}"
                            class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-eye"></i></small>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $orders->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
