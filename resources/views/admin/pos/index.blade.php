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
<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.sale_lists')}}</h4>
    <span class="text-muted form-text">( Showing {{ $orders->count() }} of total {{ $orders->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <div class="d-flex align-items-end">
        <div class="d-flex flex-wrap mb-2">
            <div class="filter-content">
                <form action="{{ route('admin.pos.index') }}" class="d-flex flex-wrap align-items-end">
                    @if(auth()->user()->role->hasPermission('create-order'))
                        <div class="form-group me-2">
                            <a href="{{ route('admin.pos.create') }}" class="btn btn-secondary">
                                <small class="me-2"><i class="fa fa-plus"></i></small>
                                <span>Add New</span>
                            </a>
                        </div>
                    @endif
{{--                    <div class="form-group me-2">--}}
{{--                        <select name="delivery" class="form-select">--}}
{{--                            <option value="">Select Delivery</option>--}}
{{--                            @foreach($deliveries as $delivery)--}}
{{--                            <option value="{{ $delivery->id }}" {{ request()->delivery == $delivery->id ? 'selected' : '' }}>--}}
{{--                                {{ $delivery->name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    <div class="form-group me-2">
                        <input type="date" name="from_date" class="form-control form-control-sm"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group me-2">
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-outline-secondary me-2">Filter</button>
                        <a href="{{ route('admin.pos.index') }}" class="btn btn-danger">
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
                    <th>Date</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <?php $total = $order->return() ? $order->getSubTotal() - $order->return()->price : $order->getSubTotal(); ?>
                <tr id="tr-{{ $order->id }}" class="align-middle">
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
                        <a href="#" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#payment-modal-{{ $order->id }}">
                            <small>Pay</small>
                        </a>

                        <payment-form :order="{{ $order }}"></payment-form>

                        @endif

                        @endif
                    </td>
                    {{-- <td>
                        <?php $balance = $order->getBalance() + $order->getChange(); ?>
                        @if( $order->getBalance() <= 0)
                        <span class="text-success">Paid</span>
                        @else
                        <span class="text-danger me-1">{{ number_format($order->getPayAmount() - $order->getReturnAmount() - $order->getChange()  == $total && abs($order->getBalance()) == 0 ? 0 : $order->getBalance() + $order->getChange()) }}</span>

                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() != $total && abs($order->getBalance()) != 0 && $order->status->slug != 'cancel')
                        <a href="#" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#payment-modal-{{ $order->id }}">
                            <small>Pay</small>
                        </a>

                        <payment-form :order="{{ $order }}"></payment-form>

                        @endif

                        @endif
                    </td> --}}
                    <td>
                        {{ $order->updated_at ? $order->updated_at->format('m/d/Y') : $order->created_at->format('m/d/Y') }}
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-order') && $order->status->slug != 'cancel')
                        <a href="{{ route('admin.pos.create') }}?order_no={{ $order->order_no ?? $order->id }}">
                            <i class="fa fa-eye"></i>
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
