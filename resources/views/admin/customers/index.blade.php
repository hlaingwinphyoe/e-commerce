@extends('layouts.admin')

@section('title', 'Customers')

@section('classes', 'admin admin-customers admin-customers-index')

@section('content')

<x-admin.search-box url="{{ route('admin.customers.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.customer')}}</h4>
        <span class="text-muted form-text">( Showing {{ $users->count() }} of total {{ $users->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">

        @if(auth()->user()->role->hasPermission('create-user'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.customers.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-customer'))
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
        <form action="{{ route('admin.customers.index') }}" class="d-flex responsive-flex">
            <div class="form-group me-2">
                <select name="role" class="form-select">
                    <option value="">Select Roles</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
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
                    <th>Phone</th>
                    @if(auth()->user()->role->hasPermission('edit-customer'))
                    <th width="150px">Role</th>
                    @endif
                    <th>Points</th>
                    @if(auth()->user()->role->hasPermission('delete-customer'))
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @if(auth()->user()->hasRole('admin') || $user->role->slug != 'admin')
                <tr id="tr-{{ $user->id }}">
                    <td><input type="checkbox" id="check-{{ $user->id }}" value="{{ $user->id }}"></td>
                    <td>
                        <p class="mb-0">{{ $user->name }}</p>
                        <small class="text-primary">{{ $user->email }}</small>
                    </td>
                    <td>{{ $user->phone }}</td>
                    @if(auth()->user()->role->hasPermission('edit-customer'))
                    <td>
                        <form action="{{ route('admin.update-user-role', $user->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <select name="role" id="role-select-{{ $user->id }}" class="role-select form-select">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </td>
                    @endif
                    <td>{{ $user->points }}</td>
                    @if(auth()->user()->role->hasPermission('delete-customer'))
                    <td>
                        <a href="#user-edit-{{ $user->id }}" data-bs-toggle="modal" class="btn btn-sm btn-dark mr-1 mb-1">
                            <span><i class="fa fa-pencil-alt"></i></span>
                        </a>
                        @include('admin.users.edit')
                        <a href="#delete-modal-{{ $user->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $user->id }}" url="{{ route('admin.users.destroy', $user->id) }}"></x-admin.delete>
                    </td>
                    @endif
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="4" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $users->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@if(auth()->user()->role->hasPermission('delete-customer'))
<x-admin.delete-all url="/wapi/users"></x-admin.delete-all>
@endif
@endsection