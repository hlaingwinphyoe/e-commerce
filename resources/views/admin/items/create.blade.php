@extends('layouts.admin')

@section('title', 'Items')

@section('classes', 'admin admin-items admin-items-create')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Item</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.items.store') }}" method="post">
                @csrf

                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-3">Item Information</h5>

                    <div class="row flex-wrap pb-3">

                        <div class="col-md-3 form-group me-2 w-sm-100">
                            <label for="">
                                Name
                                <span class="text-danger">**</span>
                            </label>

                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                            <div class="py-1"><small class="help-text mm-font text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small></div>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-2 form-group me-2 w-sm-100">
                            <label for="">
                                Unit
                            </label>

                            <select name="unit" class="form-select form-select-sm">
                                <option value="">Choose Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <div class="py-1"><small class="help-text mm-font text-muted">Unit ရှိလျှင်ထည့်ပါ။</small></div>
                            @error('unit')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-2 form-group me-2 w-sm-100">
                            <label for="">
                                Brand
                            </label>

                            <select name="brand" class="form-select form-select-sm">
                                <option value="">Choose Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <div class="py-1"><small class="help-text mm-font text-muted">Brand ရှိလျှင်ထည့်ပါ။</small></div>
                            @error('brand')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3 form-group me-2 w-sm-100">
                            <label for="">
                                Category
                                <span class="text-danger">**</span>
                            </label>
                            {{-- <search-or-create url="types" name="type"></search-or-create> --}}

                            <select class="form-select" name="type" id="custom-select" data-placeholder="Choose Category">
                                <option></option>
                                @forelse ($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @empty
                                    <option value="0">No Category</option>
                                @endforelse
                            </select>

                            <div class="py-1"><small class="help-text mm-font text-muted">အမျိုးအစား ထည့်ပါ။</small></div>
                            @error('type')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <small class="me-2"><i class="fas fa-save"></i></small>
                        <span>Add Pricing</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
