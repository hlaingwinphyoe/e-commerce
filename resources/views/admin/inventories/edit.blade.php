@extends('layouts.admin')

@section('title', 'Purchases')

@section('classes', 'admin admin-inventories admin-inventories-edit')

@section('content-header')
<x-admin.content-header :navs="['inventories', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2"><i class="fa fa-arrow-left"></i></a>
        <h4 class="page-title mb-0 me-2">Purchases</h4>
        <span class="text-muted form-text">( Edit )</span>

        @if(!$inventory->is_published && false)
        <a href="#publish-item-{{ $inventory->id }}" class="ms-2 me-2 btn btn-sm btn-danger text-white fw-bold mb-1" data-bs-toggle="modal">
            <span>ရောက်ရှိသည်</span>
        </a>
        <x-admin.publish-item id="{{ $inventory->id }}"></x-admin.publish-item>
        @endif
    </div>

    <form class="d-flex" action="{{ route('admin.inventories.update', $inventory->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="form-group mb-3 me-2">
            <label for="">Supplier</label>
            <select name="supplier_id" class="form-select">
                <option value="">Choose Supplier</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $inventory->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group-mb-3 me-2">
            <label for="">Date</label>
            <input type="date" class="form-control" name="date" value="{{ $inventory->date }}">
        </div>

        <div class="form-group mb-3 me-2 align-self-end">
            <button type="submit" class="btn btn-sm btn-primary">
                <small><i class="fa fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>

    </form>


    <div>
        <div class="mb-2">
            @if(!$inventory->is_published)
            <a href="#publish-item-{{ $inventory->id }}" class="me-2 btn btn-sm btn-danger text-white fw-bold mb-1" data-bs-toggle="modal">
                <span>ရောက်ရှိသည်</span>
            </a>
            <x-admin.publish-item id="{{ $inventory->id }}"></x-admin.publish-item>
            @endif
            <a href="/admin/inventories" class="btn btn-sm btn-outline-info">မှာယူပါမည်</a>
        </div>
        <add-sku :inventory="{{ $inventory }}" :skus="{{ $inventory->skus()->with(['item'])->get() }}"></add-sku>
    </div>


</div>

@endsection