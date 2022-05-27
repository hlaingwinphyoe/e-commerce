@extends('layouts.admin')

@section('title', 'Purchase')

@section('classes', 'admin admin-inventory admin-inventory-edit')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">{{ __('menu.purchase') }}</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>

    <!-- Sku Forms -->
    <purchase :suppliers="{{ $suppliers }}" :inventory="{{ $inventory }}" :skus="{{ $inventory->skus }}"></purchase>

</div>

@endsection