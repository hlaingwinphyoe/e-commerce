@extends('layouts.admin')

@section('title', 'Staff')

@section('classes', 'admin admin-users admin-users-index')

@section('content')

<x-admin.search-box url="{{ route('admin.users.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.staff')}}</h4>
    <span class="text-muted form-text">( Showing {{ $users->count() }} of total {{ $users->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">

    <div class="d-flex">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-user'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.users.create') }}" class="btn btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            {{-- <div class="me-2 mb-1">
                <form action="{{ route('admin.users.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-btn-wrapper">
                        <button class="btn btn-success">
                            <small class="me-1"><i class="far fa-file-excel"></i></small>
                            <span>Import</span>
                        </button>
                        <input type="file" name="files" id="excel-input">
                    </div>
                </form>
            </div> --}}
            <form action="{{ route('admin.users.index') }}" class="d-flex responsive-flex">
                <div class="form-group me-2">
                    <select name="role" class="form-select">
                        <option value="">Select Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-secondary me-2">Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">
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
                    <th>Phone</th>
                    <th width="150px">Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr id="tr-{{ $user->id }}" class="align-middle">
                    <td>
                        <p class="mb-0">{{ $user->name }}</p>
                        <small class="text-primary">{{ $user->email }}</small>
                    </td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        @if($user->role->slug != 'admin')
                        <form action="{{ route('admin.update-user-role', $user->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <select name="role" id="role-select-{{ $user->id }}" class="role-select form-select">
                                    @foreach($roles as $role)
                                    @if(($role->slug == 'admin' || $role->slug == 'technician'))
                                    @if(auth()->user()->role->slug == 'technician')
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endif
                                    @else
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        @else
                        <span class="badge bg-success">{{ $user->role->name }}</span>
                        @endif
                    </td>
                    <td>
                        @if($user->role->slug != 'admin')
                        <a href="#user-edit-{{ $user->id }}" data-bs-toggle="modal" class="me-2 text-warning">
                            <span><i class="fa fa-pencil-alt"></i></span>
                        </a>
                        @include('admin.users.edit')
                        <a href="#delete-modal-{{ $user->id }}" class="" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $user->id }}" url="{{ route('admin.users.destroy', $user->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $users->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
