@extends('layouts.admin')

@section('title', 'Sale Lists')

@section('classes', 'admin admin-pos admin-pos-index')

@section('content')

<x-admin.search-box url="{{ route('admin.pos.index') }}"></x-admin.search-box>

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

            <form action="{{ route('admin.pos.index') }}" class="d-flex responsive-flex">

                <div class="form-group me-2">
                    <input type="text" name="customer" class="form-control form-control-sm"
                        placeholder="Search with name/phone" value="{{ request()->customer }}">
                </div>

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
                    <select name="status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                        @if($status->slug != 'confirmed')
                        <option value="{{ $status->id }}" {{ request()->status == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}</option>
                        @endif
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
                    <th>Customer</th>
                    <th>Price</th>
                    <th>ကျသင့်ငွေ</th>
                    <th>ရရန်ကျန်ငွေ</th>
                    <th>မြတ်ငွေ</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Delivery</th>
                    <th>By</th>
                    <th>Date</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <?php $total = $order->return() ? $order->getSubTotal() - $order->return()->price : $order->getSubTotal(); ?>
                <tr id="tr-{{ $order->id }}">
                    <td>
                        <p class="mb-0">{{ $order->order_number }}</p>
                    </td>
                    <td>
                        @if($order->customer)
                        <p class="mb-0">{{ $order->customer->name }}</p>
                        <div class="text-danger small">{{ $order->customer->phone }}</div>
                        <span class="text-primary">{{ $order->buyer ? $order->buyer->role->name : '' }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($order->price) }}</td>
                    <td>
                        {{ number_format($total) }}
                    </td>
                    <td>
                        <?php $balance = $order->getBalance() + $order->getChange(); ?>
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() == $total &&
                        abs($order->getBalance()) == 0)
                        <span class="text-success">Paid</span>
                        @else
                        <span
                            class="text-danger">{{ number_format($order->getPayAmount() - $order->getReturnAmount() - $order->getChange()  == $total && abs($order->getBalance()) == 0 ? 0 : $order->getBalance() + $order->getChange()) }}</span>
                        @endif
                    </td>
                    <td>
                        {{ number_format($order->getProfit()) }}
                    </td>
                    <td>{{ $order->status->name }}</td>
                    <td><span>{{ $order->type }}</span></td>
                    <td>
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
                    <td>{{ $order->seller ? $order->seller->name : '-' }}</td>
                    <td>
                        {{ $order->updated_at ? $order->updated_at->format('d M, Y') : $order->created_at->format('d M, Y') }}
                        <p class="mb-0">
                            {{ $order->updated_at ? $order->updated_at->format('H:i A') : $order->created_at->format('H:i A') }}
                        </p>
                    </td>
                    <td>
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() != $total &&
                        abs($order->getBalance()) != 0 && $order->status->slug != 'cancel')
                        <a href="#payment-modal-{{ $order->id }}" class="btn btn-sm btn-secondary me-2 mb-1"
                            data-bs-toggle="modal">
                            <small>Payment</small>
                        </a>
                        @include('admin.sales.payment')
                        @endif
                        @if(auth()->user()->role->hasPermission('access-order'))
                        <a href="{{ route('admin.pos.show', $order->id) }}" class="btn btn-sm btn-outline-danger me-2">
                            <i class="fa fa-eye"></i>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('edit-order') && $order->status->slug != 'cancel')
                        <a href="{{ route('admin.pos.create') }}?order_no={{ $order->order_no ?? $order->id }}"
                            class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        <a href="{{ route('admin.sales.print', $order->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-print"></i></small>
                        </a>
                        <?php /*<a href="{{ route('save-invoice', $order->id) }}" class="btn btn-sm btn-info me-2 mb-1">
                            <small><i class="fa fa-receipt"></i></small>
                        </a> */ ?>
                        @if(auth()->user()->role->hasPermission('delete-order'))
                        <a href="#delete-modal-{{ $order->id }}" class="btn btn-sm btn-danger mb-1 me-2"
                            data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $order->id }}" url="{{ route('admin.orders.destroy', $order->id) }}">
                        </x-admin.delete>
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
