@extends('layouts.admin')

@section('title', 'Items')

@section('classes', 'admin admin-items admin-items-create')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Item</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.items.store') }}" method="post">
        @csrf

        <div class="py-2 mb-3">
            <h5 class="text-primary mb-3">Item Information</h5>
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

            <div class="form-group">
                <label for="">
                    Code
                </label>
                <small class="help-text text-muted">ရှိလျှင်ထည့်ပါ။ တူ၍မရပါ။</small>
                <input type="text" name="code" class="form-control form-control-sm" placeholder="Code" value="{{ old('code') }}">
                @error('code')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">
                    Category
                    <span class="text-danger">**</span>
                </label>
                <small class="help-text text-muted">Category ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                <select name="type" class="form-select form-select-sm">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group d-none">
                <label for="">
                    Unit
                </label>
                <small class="help-text text-muted">Unit ရွေးပါ။ ရှိလျှင်ထည့်ပါ။</small>
                <select name="unit" class="form-select form-select-sm">
                    <option value="">Choose Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
                @error('unit')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group d-none">
                <label for="">
                    Per Unit
                </label>
                <small class="help-text text-muted">Unit ရွေးခဲ့လျှင် တယူနစ်တွင် ပါဝင်သော ပမာဏထည့်ပါ။ (ဥပမာ- တဖာလျှင် ၂၄ခု ပါပါက 24 ဟုထည့်ပါ။) </small>
                <input type="text" class="form-control form-control-sm" placeholder="Per Unit" name="per_unit" value="{{ old('per_unit') }}">
                @error('per_unit')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">
                    Brand
                </label>
                <small class="help-text text-muted">Brand ရွေးပါ။ ရှိလျှင်ထည့်ပါ။</small>
                <select name="brand" class="form-select form-select-sm">
                    <option value="">Choose Brand</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group d-none">
                <label for="">
                    Stock
                </label>
                <small class="help-text text-muted">အနည်းဆုံးရှိရမည့် ပမာဏထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                <input type="text" name="stock" class="form-control form-control-sm" placeholder="Stock" value="{{ old('stock') }}">
                @error('stock')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Description</label>
                <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။ (စာများအား ကော်ပီကူးပြီးလျှင် remove font style လုပ်ပေးပါ)</small>
                <textarea name="desc" class="form-control form-control-sm text-editor" rows="3" placeholder="Description">{{ old('desc') }}</textarea>
            </div>

        </div>

        <div class="from-group">
            <button type="submit" class="btn btn-sm btn-secondary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Add Pricing</span>
            </button>
        </div>
    </form>

</div>

@endsection