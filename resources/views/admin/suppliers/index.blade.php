@extends('layouts.admin')

@section('title', 'Suppliers')

@section('classes', 'admin admin-suppliers admin-suppliers-index')

@section('content')

<x-admin.search-box url="{{ route('admin.skus.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.suppliers')}}</h4>
        <span class="text-muted form-text">( Showing {{$suppliers->count()}} of total {{$suppliers->total()}} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-supplier'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-supplier'))
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
    </div>

    @include('components.admin.message')

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Supplier Name</th>
                    <th>Phone</th>

                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr id="tr-{{ $supplier->id }}">
                    <td><input type="checkbox" id="check-{{ $supplier->id }}" value="{{ $supplier->id }}"></td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>

                    <td>
                        @if(auth()->user()->role->hasPermission('edit-supplier'))
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-supplier'))
                        <a href="#delete-modal-{{ $supplier->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $supplier->id }}" url="{{ route('admin.suppliers.destroy', $supplier->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{$suppliers->appends(request()->query->all())->links('components.pagination')}}
    </div>
</div>

@endsection