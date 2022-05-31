@extends('layouts.admin')

@section('title', 'Sale Lists')

@section('classes', 'admin admin-pos admin-pos-index')

@section('content')

<x-admin.search-box url="{{ route('admin.pos.index') }}"></x-admin.search-box>

<div>
    <div class="mb-2">
        <div class="d-flex">
            <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.sale_lists')}}</h4>
            <span class="text-muted form-text">( Showing {{ $orders->count() }} of total {{ $orders->total() }} records )</span>
        </div>
    </div>

    <div class="d-flex flex-wrap">
        @if(auth()->user()->role->hasPermission('create-order'))
        <div class="me-2 d-none mb-3">
            <a href="{{ route('admin.pos.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-order'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif
        <form action="{{ route('admin.pos.index') }}" class="d-flex flex-wrap">

            <div class="form-group me-2">
                <input type="text" name="customer" class="form-control form-control-sm" placeholder="Search with name/phone" value="{{ request()->customer }}">
            </div>

             <div class="form-group me-2">
                <select name="delivery" class="form-select">
                    <option value="">Select Delivery</option>
                    @foreach($deliveries as $delivery)
                    <option value="{{ $delivery->id }}" {{ request()->delivery == $delivery->id ? 'selected' : '' }}>{{ $delivery->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group me-2">
                <select name="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                    @if($status->slug != 'confirmed')
                    <option value="{{ $status->id }}" {{ request()->status == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
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

    @include('components.admin.message')

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
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
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
                    <td><input type="checkbox" id="check-{{ $order->id }}" value="{{ $order->id }}"></td>
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
                        {{ number_format($order->getSubTotal()) }}
                    </td>
                    <td>
                        <?php $balance = $order->getBalance() + $order->getChange(); ?>
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() == $total && abs($order->getBalance()) == 0)
                        <span class="text-success">Paid</span>
                        @else
                        <span class="text-danger">{{ number_format($order->getPayAmount() - $order->getReturnAmount() - $order->getChange()  == $total && abs($order->getBalance()) == 0 ? 0 : $order->getBalance() + $order->getChange()) }}</span>
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
                                <select name="delivery" id="role-select-{{ $order->id }}" class="role-select form-select">
                                    <option value="0" selected disabled>Choose Delivery</option>
                                    @foreach($deliveries as $delivery)

                                    <?php
                                    if ($order->deliveries->count()) {
                                        $order_delivery = $order->deliveries->first()->id;
                                    } else {
                                        $order_delivery = 0;
                                    }

                                    ?>

                                    <option value="{{ $delivery->id }}" {{$order_delivery == $delivery->id ? 'selected' : ''}}>{{ $delivery->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </td>
                    <td>{{ $order->seller ? $order->seller->name : '-' }}</td>
                    <td>
                        {{ $order->updated_at ? $order->updated_at->format('d M, Y') : $order->created_at->format('d M, Y') }}
                        <p class="mb-0">{{ $order->updated_at ? $order->updated_at->format('H:i A') : $order->created_at->format('H:i A') }}</p>
                    </td>
                    <td>
                        @if($order->getPayAmount() - $order->getReturnAmount() - $order->getChange() != $total && abs($order->getBalance()) != 0 && $order->status->slug != 'cancel')
                        <a href="#payment-modal-{{ $order->id }}" class="btn btn-sm btn-secondary me-2 mb-1" data-bs-toggle="modal">
                            <small>Payment</small>
                        </a>
                        @include('admin.pos.payment')
                        @endif
                        @if(auth()->user()->role->hasPermission('access-order'))
                        <a href="{{ route('admin.pos.show', $order->id) }}" class="btn btn-sm btn-outline-danger me-2">
                            <i class="fa fa-eye"></i>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('edit-order') && $order->status->slug != 'cancel')
                        <a href="{{ route('admin.pos.create') }}?order_no={{ $order->order_no ?? $order->id }}" class="btn btn-sm btn-primary me-2 mb-1">
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
                        <a href="#delete-modal-{{ $order->id }}" class="btn btn-sm btn-danger mb-1 me-2" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $order->id }}" url="{{ route('admin.orders.destroy', $order->id) }}"></x-admin.delete>
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
@if(auth()->user()->role->hasPermission('delete-order'))
<x-admin.delete-all url="/wapi/orders"></x-admin.delete-all>
@endif
@endsection