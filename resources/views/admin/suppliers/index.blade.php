@extends('layouts.admin')

@section('title', 'Suppliers')

@section('classes', 'admin admin-suppliers admin-suppliers-index')

@section('content')

<x-admin.search-box url="{{ route('admin.suppliers.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.suppliers')}}</h4>
    <span class="text-muted form-text">( Showing {{ $suppliers->count() }} of total {{ $suppliers->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">

    <div class="d-flex">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-supplier'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Supplier Name</th>
                    <th>Phone</th>

                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr id="tr-{{ $supplier->id }}">
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>

                    <td>
                        @if(auth()->user()->role->hasPermission('edit-supplier'))
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="me-2 text-warning">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-supplier'))
                        <a href="#delete-modal-{{ $supplier->id }}" class="text-danger" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $supplier->id }}" url="{{ route('admin.suppliers.destroy', $supplier->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{$suppliers->appends(request()->query->all())->links('components.pagination')}}
    </div>
</div>

@endsection
