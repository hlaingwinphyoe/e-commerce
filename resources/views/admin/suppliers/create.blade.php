@extends('layouts.admin')

@section('title', 'Suppliers')

@section('classes', 'admin admin-suppliers admin-suppliers-create')

@section('content-header')
<x-admin.content-header :navs="['suppliers', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Supplier</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>

    <form action="{{ route('admin.suppliers.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Supplier Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Phone Number
                        </label>
                        <small class="help-text text-muted">ကုမ္ပဏီအမည်ထည့်ပါ။</small>
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone" value="{{ old('phone') }}">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="from-group">
            <button brand="submit" class="btn btn-sm btn-secondary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>
    </form>

</div>

@endsection