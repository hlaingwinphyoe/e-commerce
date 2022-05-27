@extends('layouts.admin')

@section('title', 'Regions')

@section('classes', 'admin admin-regions admin-regions-create')

@section('content-header')
<x-admin.content-header :navs="['regions', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Region</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.regions.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name(In English)
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                     <div class="form-group">
                        <label for="">
                            Name(In Myanmar)
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="mm_name" class="form-control form-control-sm" placeholder="Name" value="{{ old('mm_name') }}">
                        @error('mm_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">
                            Country
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Country ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                        <select name="country" class="form-select form-select-sm">
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="desc" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('desc') }}</textarea>
                    </div>
                    </div>
            </div>
        </div>

        <div class="from-group">
            <button region="submit" class="btn btn-sm btn-secondary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>
    </form>



</div>

@endsection
