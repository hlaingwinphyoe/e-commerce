@extends('layouts.admin')

@section('title', 'Purchases')

@section('classes', 'admin admin-inventories admin-inventories-index')

@section('content')

<x-admin.search-box url="{{ route('admin.inventories.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.purchase')}}</h4>
        <span class="text-muted form-text">( Showing {{ $inventories->count() }} of total {{ $inventories->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-1">
        @if(auth()->user()->role->hasPermission('create-inventory'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.inventories.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-inventory'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif

        <form action="{{ route('admin.inventories.index') }}" class="d-flex responsive-flex">
            <input type="hidden" name="disabled" value="{{ request('disabled') }}">
            <div class="form-group">
                <search-item></search-item>
            </div>
            <div class="form-group me-2">
                <select name="supplier_id" class="form-select">
                    <option value="">Select Supplier Name</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <select name="is_published" class="form-select">
                    <option value="">Select Status</option>
                    <option value="is_published" {{ request()->is_published == 'is_published' ? 'selected' : '' }}>ရောက်ရှိပြီး</option>
                    <option value="pending" {{ request()->is_published == 'pending' ? 'selected' : '' }}>မှာယူထား</option>
                </select>
            </div>
            <div class="form-group me-2">
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Search Date" class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.inventories.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

    @include('components.admin.message')

    @include('components.admin.errors')

    <?php
    $query = '';
    $query .= request('sku_id') ? '?sku_id=' . request('sku_id') : '';
    if (request('supplier_id')) {
        $query .= $query ? '&' : '?';
        $query .= 'supplier_id=' . request('supplier_id');
    }
    if (request('date')) {
        $query .= $query ? '&' : '?';
        $query .= 'date=' . request('date');
    }
    ?>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Supplier</th>
                    <th>Amount</th>
                    <th>ပေးရန်ကျန်ငွေ</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>By</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr id="tr-{{ $inventory->id }}">
                    <td><input type="checkbox" id="check-{{ $inventory->id }}" value="{{ $inventory->id }}"></td>
                    <td>{{ $inventory->supplier ? $inventory->supplier->name : '' }}</td>
                    <td>{{ number_format($inventory->getAmount()) }}</td>
                    <td>
                        <span class="{{ $inventory->getBalance() > 0 ? 'text-danger' : 'text-success' }}">{{ $inventory->getBalance() > 0 ? number_format($inventory->getBalance()) : 'Paid' }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($inventory->date)->format('d M, Y') }}</td>
                    <td>
                        <small class="{{ $inventory->is_published == 1 ? 'text-success' : 'text-info' }}">{{ $inventory->is_published == 1 ? 'ရောက်ရှိပြီး' : 'မှာယူထား' }}</small>
                    </td>
                    <td>{{ $inventory->user->name }}</td>

                    <td>
                        @if(auth()->user()->role->hasPermission('edit-inventory'))
                        @if(!$inventory->is_published)
                        <a href="#publish-item-{{ $inventory->id }}" class="me-2 btn btn-sm btn-info text-white fw-bold mb-1" data-bs-toggle="modal">
                            <span>ရောက်ရှိသည်</span>
                        </a>
                        <x-admin.publish-item id="{{ $inventory->id }}"></x-admin.publish-item>
                        @endif

                        <a href="{{ route('admin.inventories.edit', $inventory->id) }}" class="btn btn-sm btn-primary mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif

                        @if($inventory->getBalance() > 0)
                        <a href="#payment-modal-{{ $inventory->id }}" class="btn btn-sm btn-secondary me-2 mb-1" data-bs-toggle="modal">
                            <small>Payment</small>
                        </a>
                        @include('admin.inventories.payment')
                        @endif


                        @if(auth()->user()->role->hasPermission('delete-inventory'))
                        <a href="#delete-modal-{{ $inventory->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $inventory->id }}" url="{{ route('admin.inventories.destroy', $inventory->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{$inventories->appends(request()->query->all())->links('components.pagination')}}
    </div>
</div>

<x-admin.delete-all url="/wapi/inventory"></x-admin.delete-all>

@endsection