@extends('layouts.admin')

@section('title', 'Deliveries')

@section('classes', 'admin admin-deliveries admin-deliveries-edit')

@section('content-header')
<x-admin.content-header :navs="['deliveries', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Delivery</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.deliveries.update', $delivery->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') ?? $delivery->name }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Phone</label>
                        <small class="help-text text-muted">ဖုန်းနံပါတ်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone" value="{{ old('phone') ?? $delivery->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <small class="help-text text-muted">နေရပ်လိပ်စာထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="address" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('address') ?? $delivery->address }}</textarea>
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
