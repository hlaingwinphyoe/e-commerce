@extends('layouts.admin')

@section('title', 'Coupons')

@section('classes', 'admin admin-coupons admin-coupons-edit')

@section('content-header')
<x-admin.content-header :navs="['coupons', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-danger me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Coupon</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="form-group d-none">
            <button type="submit" class="btn btn-sm btn-dark">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-2 mb-3">
                    <h5 class="text-danger mb-3">Coupon Information</h5>
                    <div class="form-group">
                        <label for="">
                            Code
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Codeထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Coupon Code" value="{{ old('code') ?? $coupon->code }}">
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
                            <input type="text" name="amt" class="form-control form-control-sm" placeholder="Discount Amount" value="{{ $coupon->type == 'fixed' ? $coupon->value: $coupon->percent_off }}">
                            <div class="input-group-text bg-white">
                                <select name="type" class="form-select border-0">
                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Percent</option>
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
                            <option value="{{ $type->id }}" {{ $coupon->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                        <input type="date" class="form-control form-control-sm" name="expired" placeholder="Expired Date" value="{{ old('expired') ?? $coupon->expired }}">
                        @error('expired')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>                    
                </div>               
            </div>

            <div class="col-md-4 mb-2">
                <div class="bg-sidebar px-3 py-2 mb-3">
                    <h5 class="text-danger mb-3">Featured Image</h5>
                    <media-upload :images="{{ $coupon->medias->pluck('id') }}"></media-upload>
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
