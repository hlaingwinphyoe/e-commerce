@extends('layouts.admin')

@section('title', 'Roles')

@section('classes', 'admin admin-roles admin-roles-index')

@section('content')

<x-admin.search-box url="{{ route('admin.roles.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.role')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $roles->count() }}</span> of total <span class="">{{ $roles->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-role'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.roles.index') }}" class="d-flex responsive-flex">
                <div class="form-group me-2">
                    <select name="type" class="form-select">
                        <option value="">Select Roles</option>
                        <option value="Operation" {{ request('type') == 'Operation' ? 'selected' : '' }}>Operation Role</option>
                        <option value="Customer" {{ request('type') == 'Customer' ? 'selected' : '' }}>Customer Role</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-primary">
                        <small><i class="fa fa-redo"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr id="tr-{{ $role->id }}">
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->type }} Role</td>
                    <td>{{ $role->permissions()->count() }}</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-role'))
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="me-2 text-warning">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-role'))
                        <a href="#delete-modal-{{ $role->id }}" data-bs-toggle="modal">
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
@endsection
