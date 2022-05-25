@extends('layouts.admin')

@section('title', 'Item')

@section('classes', 'admin admin-item item-index')

@section('content')

<x-admin.search-box url="{{ route('admin.items.index') }}"></x-admin.search-box>

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.item')}}</h2>
    <p>Showing {{ $items->count() }} of total {{ $items->total() }} results.</p>
</div>

@include('components.admin.message')

<?php $item_count =  \App\Models\Item::withTrashed()->count(); ?>

@if($item_count >= config('app.max_data'))
<div class="">
    <p class="alert alert-danger py-1">You have already <span class="fw-bold h5">{{ config('app.max_data') }}</span> items (Including in Trash and Disabled) and can't add anymore.</p>
</div>
@endif

<!-- filter -->
<div class="d-flex flex-wrap mb-2">
    @if(auth()->user()->role->hasPermission('create-item') && $item_count <= config('app.max_data')) <div class="me-2 mb-1">
        <a href="{{ route('admin.items.create') }}" class="btn btn-sm btn-primary">
            <small><i class="fa fa-plus"></i></small>
            <span>Add New</span>
        </a>
</div>
@endif

<form action="{{ route('admin.items.index') }}" method="get" class="d-flex">
    <div class="me-2 mb-1">
        <select name="status" class="form-select">
            <option value="">Select Status</option>
            <option value="all">All</option>
            <option value="disabled">Disabled</option>
            <option value="trashed">Trashed</option>
        </select>
    </div>
    <div class="me-2 mb-1">
        <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="item">
            <i class="fa fa-check me-2"></i>
            <span>Apply</span>
        </button>
    </div>
</form>

<form action="{{ route('admin.items.index') }}" class="d-flex responsive-flex">
    <input type="hidden" name="disabled" value="{{ request('disabled') }}">

    <div class="form-group me-2">
        <select name="type" class="form-select">
            <option value="">Choose Category</option>
            @foreach($types as $type)
            <option value="{{ $type->id }}" {{ $type->id == request()->type? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
        <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-primary mb-1">
            <small><i class="fa fa-redo m-0"></i></small>
        </a>
    </div>
</form>
</div>

<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
if (request('type')) {
    $query .= $query ? '&' : '?';
    $query .= 'type=' . request('type');
}
if (request('chemical')) {
    $query .= $query ? '&' : '?';
    $query .= 'chemical=' . request('chemical');
}
if (request('vendor')) {
    $query .= $query ? '&' : '?';
    $query .= 'vendor=' . request('vendor');
}
if (request('country')) {
    $query .= $query ? '&' : '?';
    $query .= 'country=' . request('country');
}
if (request('brand')) {
    $query .= $query ? '&' : '?';
    $query .= 'brand=' . request('brand');
}
?>


<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th width="250px">Name</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Skus</th>
                <th>Price</th>
                <th>Discount</th>
                <th>By</th>
                <th><i class="fa fa-border-style"></i></th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr id="tr-{{ $item->id }}">
                <td class="">{{ $item->name }}</td>
                <td>{{ $item->brand() ? $item->brand()->name : '' }}</td>
                <td>{{ $item->type() ? $item->type()->name : '-' }}</td>
                <td>
                    @if($item->skus->count() > 0)
                    <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-sm btn-info mr-2 mb-1">
                        <span>{{ $item->skus->count() }}</span>
                    </a>
                    @else
                    <span>{{ $item->skus->count() }}</span>
                    @endif
                </td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->discount }}</td>
                <td>{{ $item->user->name }}</td>
                <td>
                    <div class="d-flex">
                        @if(auth()->user()->role->hasPermission('edit-item') && !$item->trashed())
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="action-btn me-2 text-primary">
                            <span><i class="fa fa-pencil-alt"></i></span>
                        </a>

                        <a href="{{ route('admin.skus.index') }}?item={{ $item->id }}" class="action-btn me-2 text-success">
                            <span><i class="fa fa-plus"></i></span>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-item') && !$item->trashed())
                        <a href="#delete-modal-{{ $item->id }}" class="action-btn me-2 text-danger" data-bs-toggle="modal">
                            <span><i class="fas fa-trash"></i></span>
                        </a>
                        <x-admin.delete id="{{ $item->id }}" url="{{ route('admin.items.destroy', $item->id) }}"></x-admin.delete>
                        @endif
                        @if($item->trashed() && auth()->user()->role->hasPermission('restore-item'))
                        <a href="#restore-modal-{{ $item->id }}" class="action-btn btn btn-sm btn-info mr-2 mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-sync-alt"></i></small>
                        </a>
                        <x-admin.restore id="{{ $item->id }}" url="{{ route('admin.items.restore', $item->id) }}"></x-admin.restore>
                        @endif
                        @if($item->trashed() && auth()->user()->role->hasPermission('permenent-delete-item'))
                        <a href="#force-delete-modal-{{ $item->id }}" class="action-btn btn btn-sm btn-danger mb-1 mr-2" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.force-delete id="{{ $item->id }}" url="{{ route('admin.items.delete', $item->id) }}"></x-admin.force-delete>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">There is no result for you.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="paginate">
    {{ $items->appends(request()->query->all())->links('components.pagination') }}
</div>

@endsection