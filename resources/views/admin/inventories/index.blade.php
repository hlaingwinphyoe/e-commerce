@extends('layouts.admin')

@section('title', 'Inventory')

@section('classes', 'admin admin-inventory inventory-index')

@section('content')

<x-admin.search-box url="{{ route('admin.inventories.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.purchase')}}</h4>
    <span class="text-muted form-text">( Showing {{ $inventories->count() }} of total {{ $inventories->total() }} records )</span>
</div>

@include('components.admin.message')

<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
?>

<div class="border bg-white rounded px-2 py-4">
    
    <div class="d-flex ">
        <!-- filter -->
        <div class="d-flex flex-wrap">
            @if(auth()->user()->role->hasPermission('create-inventory'))
                <div class="me-2 mt-4">
                    <a href="{{ route('admin.inventories.create') }}" class="btn btn-secondary">
                        <small class="me-2"><i class="fa fa-plus"></i></small>
                        <span>Add New</span>
                    </a>
                </div>
            @endif
        </div>

        <form action="{{ route('admin.inventories.index') }}" class="d-flex responsive-flex align-items-end">
            <div class="form-group me-2">
                <label for="" class="form-label">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" placeholder="date">
            </div>
            <div class="form-group me-2">
                <label for="" class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="form-group me-2">
                <label for="" class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="form-group">
                <button class="btn btn-outline-secondary me-2 mb-1">Filter</button>
                <a href="{{ route('admin.inventories.index') }}" class="btn btn-danger mb-1">
                    <small><i class="fa fa-redo m-0"></i></small>
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>No.</th>
                    <th width="250px">Supplier</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>By</th>
                    <th><i class="fa fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>

                @forelse($inventories as $inventory)
                <tr id="tr-{{ $inventory->id }}" class="align-middle">
                    <td>{{ $inventory->inventory_no }}</td>
                    <td class="">{{ $inventory->supplier ? $inventory->supplier->name : '' }}</td>
                    <td><a href="{{ route('admin.inventories.show', $inventory->id) }}" class="badge bg-secondary p-2 text-decoration-none">{{ $inventory->skus->count() }}</a></td>
                    <td>{{ number_format($inventory->getAmount()) }}</td>
                    <td>{{ \Carbon\Carbon::parse($inventory->date)->format('M d, Y') }}</td>
                    <td>{{ $inventory->user ? $inventory->user->name : '' }}</td>
                    <td>
                        <div class="d-flex">
                            @if(auth()->user()->role->hasPermission('edit-inventory'))
                            <a href="{{ route('admin.inventories.edit', $inventory->id) }}" class="me-2 text-warning">
                                <span><i class="fa fa-pencil-alt"></i></span>
                            </a>
                            @endif

                            <a href="{{ route('admin.inventories.show', $inventory->id) }}" class="me-2 text-info"><i class="fa fa-eye "></i></a>

                            <a href="{{ route('admin.inventories.print', $inventory->id) }}" class="me-2 text-success"><i class="fa fa-print"></i></a>
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
