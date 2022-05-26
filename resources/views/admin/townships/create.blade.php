@extends('layouts.admin')

@section('title', 'Townships')

@section('classes', 'admin admin-townships admin-townships-create')

@section('content-header')
<x-admin.content-header :navs="['townships', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Township</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.townships.store') }}" method="post">
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
                            Region
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Region ရွေးပါ။ မဖြစ်မနေရွေးပါ။</small>
                        <select name="region_id" class="form-select">
                            <option value="">Choose Region</option>
                            @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="desc" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('desc') }}</textarea>
                    </div>

                    

                    {{-- <div class="form-group">
                        <label for="">
                            District
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">ခရိုင် ရွေးပါ။ မဖြစ်မနေရွေးပါ။</small>
                        <select name="district_id" class="form-select">
                            <option value="">Choose District</option>
                            @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
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
