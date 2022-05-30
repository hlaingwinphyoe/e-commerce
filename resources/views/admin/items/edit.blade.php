@extends('layouts.admin')

@section('title', 'Items')

@section('classes', 'admin admin-items admin-items-edit')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">{{ $item->name }} - {{ $item->code }}</h4>
    </div>

    <form action="{{ route('admin.items.update', $item->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="bg-white shadow rounded py-3 px-2 mb-4">
            <h5 class="text-secondary fw-bold mb-4"><span class="me-2 btn btn-sm btn-outline-primary"><i class="fa fa-info"></i></span> Item Information</h5>

            <div class="d-flex flex-wrap pb-3">

                <div class="form-group me-2 w-sm-100">
                    <label for="">
                        Name
                        <span class="text-danger">**</span>
                    </label>

                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ $item->name }}">
                    <div class="py-1"><small class="help-text mm-font text-muted">အမည်တူ ထည့်၍မရပါ။</small></div>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group me-2 w-sm-100">
                    <label for="">
                        Unit
                    </label>

                    <select name="unit" class="form-select form-select-sm">
                        <option value="">Choose Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    
                    @error('unit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group me-2 w-sm-100">
                    <label for="">
                        Brand
                    </label>

                    <select name="brand" class="form-select form-select-sm">
                        <option value="">Choose Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $item->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                   
                    @error('brand')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group me-2 w-sm-100">
                    <label for="">
                        Category
                        <span class="text-danger">**</span>
                    </label>
                    <search-or-create url="types" name="type" :input_obj="{{ $item->type() ? $item->type() : '' }}"></search-or-create>
            
                    @error('type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded py-3 px-2 mb-4">
            <h5 class="text-secondary fw-bold mb-4"><span class="btn btn-sm btn-outline-primary me-2"><i class="fa fa-tags"></i></span>Pricing Information</h5>
            <sku-price :item_id="{{ $item->id }}" :statuses="{{ $attributes }}" :has_attribute="{{ $item->attributes()->count() }}" :has_sku="{{ $item->skus()->count() }}"></sku-price>
        </div>

        <div class="bg-white shadow rounded py-3 px-2 mb-4">
            <h5 class="text-secondary fw-bold mb-4"><span class="btn btn-sm btn-outline-primary me-2"><i class="fa fa-percent"></i></span> Discount</h5>
            <sku-discount :discounts="{{ $discounts }}" :discountypes="{{ $discountypes }}" :roles="{{ $roles }}" :statuses="{{ $statuses }}" :item_id="{{ $item->id }}"></sku-discount>
        </div>

        <div class="bg-white shadow rounded py-3 px-2 mb-4">
            <h5 class="text-secondary fw-bold mb-4"><span class="btn btn-sm btn-outline-primary me-2"><i class="fa fa-image"></i></span> Featured Image</h5>
            <media-upload :images="{{ $item->medias()->pluck('id') }}" type="item" priority="check"></media-upload>
        </div>

        <div class="py-3 px-2 d-flex">
            <div class="form-group me-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save me-2"></i>Save</button>
            </div>
            <div class="form-group me-2">
                <a href="#add-stock-modal-{{ $item->id }}" class="btn btn-sm btn-secondary" data-bs-toggle="modal"><i class="fa fa-plus me-2"></i>Add Stock</a>
                <stock :item="{{ $item }}" :suppliers="{{ $suppliers }}"></stock>
            </div>
        </div>


    </form>
</div>

@endsection