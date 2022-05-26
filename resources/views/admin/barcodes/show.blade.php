@extends('layouts.admin')

@section('title', 'Barcodes')

@section('classes', 'admin admin-skus admin-skus-index')

@section('content')
<div class="mb-4">
    <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-arrow-left"></i>
    </a>
</div>

<div class="to-image">
    <div class="row">
        <div class="col-md-4">
            <div id="to-image" class="bg-white border px-2 py-3 text-center" data-id="{{ $sku->code }}">
                <div class="mb-2">{!! $sku->getBarCode() !!}</div>
                <p class="mb-2">{{ $sku->item ? $sku->item->name : '' }}{{ $sku->data ? '('. $sku->data .')' : '' }}</p>
                <div class="fw-bold">{{ number_format($sku->price) }} Ks</div>
            </div>
        </div>
    </div>
</div>



@endsection