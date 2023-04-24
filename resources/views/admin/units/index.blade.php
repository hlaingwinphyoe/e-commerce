@extends('layouts.admin')

@section('title', 'Units')

@section('classes', 'admin admin-units admin-units-index')

@section('content')

<x-admin.search-box url="{{ route('admin.units.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-2">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.unit')}}</h4>
        <span class="text-muted form-text">( Showing {{ $units->count() }} of total {{ $units->total() }} records )</span>
    </div>

    <div class="border bg-white rounded px-2 py-4">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-unit'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif
    
            @if(auth()->user()->role->hasPermission('delete-type'))
            <div class="me-2 mb-3">
                <select id="actions" name="action" class="form-select">
                    <option value="">Select action</option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="me-2 mb-3">
                <button id="apply-actions" class="btn btn-outline-secondary" data-route="unit">
                    <i class="fa fa-check me-2"></i>
                    <span>Apply</span>
                </button>
            </div>
            @endif
            <div class="form-group">
                <a href="{{ route('admin.types.index') }}" class="btn btn-danger">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </div>

        @include('components.admin.message')

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Name</th>
                    <th>Items</th>
                    <th><i class="fa fa-ellipsis-vertical"></i></th>
            </thead>
            <tbody>
                @forelse($units as $unit)
                <tr id="tr-{{ $unit->id }}">
                    <td><input type="checkbox" id="check-{{ $unit->id }}" value="{{ $unit->id }}"></td>
                    <td>{{ $unit->name }}</td>
                    <td>
                        @if($unit->items->count())
                        <a href="{{ route('admin.items.index') }}?unit={{ $unit->id }}" class="badge bg-success text-decoration-none">{{ $unit->items->count() }}</a>
                        @else
                        <span>{{ $unit->items->count() }}</span>
                        @endif
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-unit'))
                        <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-unit'))
                        <a href="#delete-modal-{{ $unit->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $unit->id }}" url="{{ route('admin.units.destroy', $unit->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $units->appends(request()->query->all())->links('components.pagination') }}
    </div>
    </div>
</div>
@if(auth()->user()->role->hasPermission('delete-unit'))
<x-admin.delete-all url="/wapi/units"></x-admin.delete-all>
@endif
@endsection