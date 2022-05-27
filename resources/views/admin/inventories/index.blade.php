@extends('layouts.admin')

@section('title', 'Inventory')

@section('classes', 'admin admin-inventory inventory-index')

@section('content')

<x-admin.search-box url="{{ route('admin.inventories.index') }}"></x-admin.search-box>

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.purchase')}}</h2>
</div>

@include('components.admin.message')

<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
?>

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $inventories->count() }}</span> of total <span class="">{{ $inventories->total() }}</span></p>
    <div class="d-flex mb-3">
        <!-- filter -->
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-inventory')) <div class="me-2 mb-1">
                <a href="{{ route('admin.inventories.create') }}" class="btn btn-sm btn-primary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
                @endif
        </div>

        <form action="{{ route('admin.inventories.index') }}" class="d-flex responsive-flex">

            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
                <a href="{{ route('admin.inventories.index') }}" class="btn btn-sm btn-primary mb-1">
                    <small><i class="fa fa-redo m-0"></i></small>
                </a>
            </div>
        </form>
    </div>

</div>

<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th width="250px">Supplier</th>
                <th>Items</th>
                <th>Amount</th>
                <th>Date</th>
                <th>By</th>
                <th><i class="fa fa-border-style"></i></th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $inventory)
            <tr id="tr-{{ $inventory->id }}">
                <td class="">{{ $inventory->supplier ? $inventory->supplier->name : '' }}</td>
                <td>{{ $inventory->skus->count() }}</td>
                <td>{{ number_format($inventory->getAmount()) }}</td>
                <td>{{ \Carbon\Carbon::parse($inventory->date)->format('M d, Y') }}</td>
                <td>{{ $inventory->user ? $inventory->user->name : '' }}</td>
                <td>
                    <div class="d-flex">
                        @if(auth()->user()->role->hasPermission('edit-inventory') && Carbon\Carbon::parse($inventory->date)->format('Y-m-d') == Carbon\Carbon::now()->format('Y-m-d'))
                        <a href="{{ route('admin.inventories.edit', $inventory->id) }}" class="btn btn-sm btn-outline-primary me-2">
                            <span><i class="fa fa-pencil-alt"></i></span>
                        </a>
                        @endif

                        <a href="{{ route('admin.inventories.show', $inventory->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
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
    {{ $inventories->appends(request()->query->all())->links('components.pagination') }}
</div>
</div>




@endsection