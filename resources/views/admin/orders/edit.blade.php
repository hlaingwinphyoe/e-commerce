@extends('layouts.admin')

@section('title', 'Orders')

@section('classes', 'admin admin-orders admin-orders-edit')

@section('content-header')
<x-admin.content-header :navs="['orders', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

@include('components.admin.message')

<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-2">
            <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-dark">
                <small><i class="fa fa-arrow-left"></i></small>
            </a>
        </div>
        <div class="d-flex">
            <h4 class="page-title mb-0 me-2">Order</h4>
            <span class="text-muted form-text">( Edit )</span>
        </div>
    </div>

    <div class="mb-2">
        <p class="mb-3">Date - {{ $order->created_at->format('d M, Y (D, H:m A)') }}</p>
        <form action="{{ route('admin.orders.update', $order->id) }}" method="post" class="row">
            @csrf
            @method('patch')

            <div class="form-group col-6 col-md-3">
                <select name="status_id" class="form-select">
                    <option value="">Choose Status</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6 col-md-3">
                <textarea name="remark" class="form-control form-control-sm" rows="1" placeholder="remark">{{ $order->remark }}</textarea>
            </div>

            @if(($order->status->slug != 'completed' && $order->status->slug != 'cancel') || (auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'technician'))
            <div class="form-group col-6 col-md-3">
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
            </div>
            @endif

        </form>
    </div>

    <div class="row">
        <div class="col-md-8 mb-3">
            <h3 class="mb-4">Ordered Items</h3>

            <div class="mb-3 table-responsive">
                <table class="table border-0">
                    @foreach($order->stockOrders as $stock)
                    <tr>
                        <td width="150px">
                            @if($stock->sku && $stock->sku->item)
                            <img src="{{ strpos($stock->sku->thumbnail, 'default.png')!== false ? $stock->sku->item->thumbnail : $stock->sku->thumbnail }}" alt="{{ $stock->sku->data ?? $stock->sku->item->name }}" style="max-width: 100%; max-height: 100px">
                            @else
                            <img src="{{ asset('images/default.png') }}" alt="Default Image" style="max-width: 100%; max-height: 100px">
                            @endif
                        </td>
                        <td>
                            <p>{{ $stock->getData() ? $stock->getData()->sku->name : '' }}</p>
                            <span>{{ number_format($stock->price) }} Ks</span>
                        </td>
                        <td>
                            @if($stock->sku)
                            <edit-order :sku="{{ $stock }}" order_status="{{ $order->status->slug }}" :statuses="{{ $sku_statuses }}" qty="{{ $stock->qty }}" stock="{{ $stock->sku->stock }}"></edit-order>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>

            @if($order->status->slug != 'completed' && $order->status->slug != 'cancel')
            <div class="border-top py-3 mb-3">
                <h5>Add More</h5>
                <order order_id="{{ $order->id }}"></order>

            </div>
            @endif
            <h5 class="">
                <button class="btn btn-sm btn-dark">
                    Total - <span id="total_price">{{ number_format($order->amount) }}</span> MMK
                </button>
            </h5>
        </div>

        <div class="col-md-4 mb-2">
            <h3 class="mb-2">Billing Information</h3>
            @if($order->data)
            <form action="{{ route('admin.orders.update-info', $order->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="tile table-responsive">
                    <table class="table table-sm table-borderless border-0">
                        <tr>
                            <td>Name</td>
                            <td>{{ $order->customer->name }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>{{ $order->customer->phone }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $order->customer->email }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>
                                <textarea name="address" class="form-control form-control-sm" rows="3">{{ $order->customer->address }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Region</td>
                            <td>
                                <select name="region_id" class="form-select">
                                    <option value="">Choose Region</option>
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ $region->id == $order->customer->region->region_id ? 'selected' : '' }}>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Township</td>
                            <td>
                                <select name="township_id" class="form-select">
                                    <option value="">Choose Township</option>
                                    @foreach($townships as $township)
                                    <option value="{{ $township->id }}" {{ $township->id == $order->customer->region->township_id ? 'selected' : '' }}>{{ $township->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ number_format($order->price) }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $order->status->name }}</td>
                        </tr>
                        <tr>
                            <td>Remark</td>
                            <td>{{ $order->customer->remark }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit" class="btn btn-sm btn-primary">Update Info</button></td>
                        </tr>
                    </table>
                </div>
            </form>
            @endif

            <payment-index :order="{{ $order }}" :statuses="{{ $price_statuses }}" :edit_order=true></payment-index>
        </div>

    </div>

</div>

@endsection