@extends('layouts.admin')

@section('title', 'Coupons')

@section('classes', 'admin admin-coupons admin-coupons-create')

@section('content-header')
<x-admin.content-header :navs="['coupons', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 mr-2">Coupons</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


     <form action="{{ route('admin.coupons.store') }}" method="post">
        @csrf       

        <div class="form-group d-none">
            <button type="submit" class="btn btn-sm btn-dark">
                <small class="mr-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="shadow px-3 py-2 mb-3">
                    <h5 class="text-danger mb-3">Coupon Information</h5>
                    <div class="form-group">
                        <label for="">
                            Code
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Codeထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Coupon Code" value="{{ old('code') }}">
                        @error('code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Amount
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Amount ထည့်ပါ။ တူ၍မရပါ။</small>
                        <div class="input-group">                           
                            <input type="text" name="amt" class="form-control form-control-sm" placeholder="Discount Amount" value="{{ old('amt') }}">
                            <div class="input-group-prepend">
                                <select name="type" class="custom-select custom-select-sm">
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent</option>
                                </select>
                            </div>
                        </div>
                        @error('amt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    

                    <div class="form-group d-none">
                        <label for="">
                            Type
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Type ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <select name="type_id" class="custom-select custom-select-sm">
                            @foreach($maintypes as $type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>                    

                    <div class="form-group">
                        <label for="">
                            Expired Date                            
                        </label>
                        <small class="help-text text-muted">Date ရွေးပါ။ ရှိလျှင်ထည့်ပါ။</small>
                        <input type="date" class="form-control form-control-sm" name="expired" placeholder="Expired Date" value="{{ old('expired') }}">
                        @error('expired')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>                    
                </div>               
            </div>

            <div class="col-md-4 mb-2">
                <div class="shadow px-3 py-2 mb-3">
                    <h5 class="text-danger mb-3">Featured Image</h5>
                    <media-upload :images="[]"></media-upload>
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
