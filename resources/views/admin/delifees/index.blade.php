@extends('layouts.admin')

@section('title', 'Deli Fees')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.delifees.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deli_fees')}}</h4>
        <span class="text-muted form-text">( Showing {{ $delifees->count() }} of total {{ $delifees->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-delifee'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.delifees.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-delifee'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
                <option value="disabled">Disabled</option>
                <option value="enabled">Enabled</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="delifee">
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
                    <th>Amt</th>
                    <th>Townships</th>
                    <th>User</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($delifees as $delifee)
                <tr id="tr-{{ $delifee->id }}">
                    <td><input type="checkbox" id="check-{{ $delifee->id }}" value="{{ $delifee->id }}"></td>
                    <td>{{ $delifee->amt }}</td>
                    <td>{{ $delifee->townships()->count() }}</td>
                    <td>
                        User
                    </td>                   
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-delifee'))
                        <a href="{{ route('admin.delifees.edit', $delifee->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-delifee'))
                        <a href="#delete-modal-{{ $delifee->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $delifee->id }}" url="{{ route('admin.delifees.destroy', $delifee->id) }}"></x-admin.delete>
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
    </div>

    <div class="paginate">
        {{ $delifees->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@if(auth()->user()->role->hasPermission('delete-delifee'))
<x-admin.delete-all url="/wapi/delifees"></x-admin.delete-all>
@endif
@endsection