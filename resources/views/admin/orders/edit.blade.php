@extends('layouts.admin')

@section('title', 'Orders')

@section('classes', 'admin admin-orders admin-orders-edit')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-2">
            <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-dark">
                <small><i class="fa fa-arrow-left"></i></small>
            </a>
        </div>
        <div class="d-flex">
            <h4 class="page-title mb-0 me-2">Order - {{ $order->order_no }}</h4>
            <span class="text-muted form-text">( Edit )</span>
        </div>
    </div>

    <web-order :order="{{ $order }}" :skus="{{ $order->skus }}"></web-order>


</div>

@endsection