@extends('layouts.admin')

@section('title', 'Skus')

@section('classes', 'admin admin-item-skus admin-item-skus-index')


@section('content')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">{{ $item->name }}</h4>
    </div>

    <div class="bg-white rounded border px-2 py-4">

        <barcode-generate :skus="{{ $item->skus()->with('item')->get() }}"></barcode-generate>
    </div>
</div>
@endsection