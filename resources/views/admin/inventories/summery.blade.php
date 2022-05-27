@extends('layouts.admin')

@section('title', 'Purchases')

@section('classes', 'admin admin-inventories admin-inventories-index')

@section('content')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Inventory Summary</h4>
    </div>
</div>

<div class="row">
    <div class="col-6 col-md-6 mb-4">
        <a href="{{ route('admin.inventories.index') }}" class="d-block px-1 py-2 bg-sidebar shadow text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-box-open"></i>
            </div>
            <span class="feature-title">Purchase Total</span>
            <p class="text-muted h2">{{ number_format($purchases_values) }}</p>
        </a>
    </div>

    <div class="col-6 col-md-6 mb-4">
        <a href="{{ route('admin.inventories.index') }}" class="d-block px-1 py-2 bg-sidebar shadow text-center rounded feature-box h-100 text-decoration-none">
            <div class="feature-icon py-3 text-primary-dark pb-2">
                <i class="fa fa-box-open"></i>
            </div>
            <span class="feature-title">Stock Total</span>
            <p class="text-muted h2">{{ number_format($stock_values) }}</p>
        </a>
    </div>
</div>


@endsection