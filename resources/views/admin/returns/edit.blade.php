@extends('layouts.admin')

@section('title', 'Return')

@section('classes', 'admin admin-returns admin-returns-edit')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Return</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <return-sku :p_return="{{ $return }}" :order_skus="{{ $return->order ? $return->order->skus : '' }}" :skus="{{ $return->skus }}"></return-sku>

</div>

@endsection