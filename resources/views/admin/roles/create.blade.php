@extends('layouts.admin')

@section('title', 'Roles')

@section('classes', 'admin admin-roles admin-roles-create')

@section('content-header')
<x-admin.content-header :navs="['roles', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Role</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.roles.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input type="radio" name="type" class="form-check-input" id="operation" value="Operation" checked>
                        <label for="operation" class="form-check-label">Operation Role</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" name="type" class="form-check-input" id="customer" value="Customer">
                        <label for="customer" class="form-check-label">Customer Role</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">
                        Name
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">အမည်ထည့်ပါ။</small>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Permissions</label>
                    <small class="help-text text-muted">Permission များသတ်မှတ်နိုင်ပါသည်။</small>
                    <permission-box :types="{{ $permissions }}" :selected_permissions="[]"></permission-box>
                </div>

                <div class="form-group d-none">
                    <label for="">Permissions</label>
                    <small class="help-text text-muted">Permission များသတ်မှတ်နိုင်ပါသည်။</small>
                    @foreach($permissions as $type=>$permis)
                    <div class="mb-2 py-2 border-bottom">
                        <h6 class="text-capitalize text-muted">{{ $type }}</h6>
                        @foreach($permis as $perm)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="perm-{{ $perm->id }}" name="permissions[]" value="{{ $perm->id }}" class="custom-control-input" @if(in_array($perm->id, old('permissions', []))) checked @endif>
                            <label class="custom-control-label" for="perm-{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="from-group">
            <button type="submit" class="btn btn-sm btn-secondary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>
    </form>



</div>

@endsection
