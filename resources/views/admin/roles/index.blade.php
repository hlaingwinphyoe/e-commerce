@extends('layouts.admin')

@section('title', 'Roles')

@section('classes', 'admin admin-roles admin-roles-index')

@section('content')

<x-admin.search-box url="{{ route('admin.roles.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">{{__('menu.role')}}</h4>
        <span class="text-muted form-text">( Showing {{ $roles->count() }} of total {{ $roles->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-role'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-role'))
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
                    <th>Name</th>
                    <th>Type</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr id="tr-{{ $role->id }}">
                    <td><input type="checkbox" id="check-{{ $role->id }}" value="{{ $role->id }}"></td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->type }} Role</td>
                    <td>{{ $role->permissions()->count() }}</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-role'))
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-role'))
                        <a href="#delete-modal-{{ $role->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $role->id }}" url="{{ route('admin.roles.destroy', $role->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $roles->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@if(auth()->user()->role->hasPermission('delete-role'))
<x-admin.delete-all url="/wapi/roles"></x-admin.delete-all>
@endif
@endsection