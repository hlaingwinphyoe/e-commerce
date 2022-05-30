@extends('layouts.admin')

@section('title', 'Units')

@section('classes', 'admin admin-units admin-units-create')

@section('content-header')
<x-admin.content-header :navs="['units', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 mr-2">Unit</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.units.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="from-group">
            <button type="submit" class="btn btn-sm btn-secondary">
                <small class="mr-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>
    </form>

</div>

@endsection