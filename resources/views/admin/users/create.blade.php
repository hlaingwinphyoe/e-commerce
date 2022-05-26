@extends('layouts.admin')

@section('title', 'Users')

@section('classes', 'admin admin-users admin-users-create')

@section('content-header')
<x-admin.content-header :navs="['users', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">User</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.users.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Phone
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">တူ၍မရပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="phone" value="{{ old('phone') }}">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Password
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">တူ၍မရပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="password" value="{{ old('password') }}">
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Role
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">role ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <select name="role_id" class="form-select">
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Email
                        </label>
                        <small class="help-text text-muted">ရှိလျှင်ထည့်ပါ။</small>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="email" value="{{ old('email') }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

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