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
        <h4 class="page-title mb-0 me-2">Item</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.items.update', $item->id) }}" method="post">
        @csrf
        @method('patch')

        <ul class="nav site-nav-tabs mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="info-tab" data-bs-toggle="tab" href="#info-content" role="tab" aria-controls="info-content" aria-selected="false">
                    Item Information
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pricing-tab" data-bs-toggle="tab" href="#pricing-content" role="tab" aria-controls="pricing-content" aria-selected="true">
                    ဈေးနှင့်ပုံများတင်ရန်
                </a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <!-- info-content -->
            <div class="tab-pane fade" id="info-content" role="tabpanel" aria-labelledby="info-tab">
                <div class="py-2 mb-3">
                    <h5 class="text-secondary mb-3">Item Information</h5>
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') ?? $item->name }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Code
                        </label>
                        <small class="help-text text-muted">ရှိလျှင်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Code" value="{{ old('code') ?? $item->code }}">
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
                        <select name="type" class="form-select">
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $item->type() && $item->type()->id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                            <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
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
                        <input type="text" class="form-control form-control-sm" placeholder="Per Unit" name="per_unit" value="{{ old('per_unit') ?? $item->per_unit }}">
                        @error('per_unit')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Brand
                        </label>
                        <small class="help-text text-muted">Brand ရွေးပါ။ ရှိလျှင်ထည့်ပါ။</small>
                        <select name="brand" class="form-select">
                            <option value="">Choose Brand</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $item->brand() && $item->brand()->id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
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
                        <input type="text" name="stock" class="form-control form-control-sm" placeholder="Stock" value="{{ old('stock') ?? $item->stock }}">
                        @error('stock')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။ <span class="text-danger">(စာများအား ကော်ပီကူးပြီးလျှင် remove font style လုပ်ပေးပါ)</span></small>
                        <textarea name="desc" class="form-control form-control-sm text-editor" rows="3" placeholder="Description">{{ old('desc') ?? $item->desc }}</textarea>
                    </div>

                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <small class="me-2"><i class="fas fa-save"></i></small>
                        <span>Save</span>
                    </button>
                </div>
            </div>

            <!-- Pricing Content -->
            <div class="tab-pane fade show active" id="pricing-content" role="tabpanel" aria-labelledby="pricing-tab">
                <div class="px-3 py-2 mb-3 border-bottom">
                    <h5 class="text-secondary mb-3">Featured Image</h5>
                    <media-upload :images="{{ $item->medias()->pluck('id') }}" type="item" priority="check"></media-upload>
                </div>

                <div class="px-3 py-2 mb-3 border-bottom">
                    <h5 class="text-secondary mb-3">Pricing Information</h5>
                    <sku-price :item_id="{{ $item->id }}" :statuses="{{ $attributes }}" :has_attribute="{{ $item->attributes()->count() }}" :has_sku="{{ $item->skus()->count() }}"></sku-price>
                </div>

                <div class="px-3 py-2 mb-3">
                    <h5 class="text-secondary mb-3">Discount</h5>
                    <sku-discount :discounts="{{ $discounts }}" :discountypes="{{ $discountypes }}" :roles="{{ $roles }}" :statuses="{{ $statuses }}" :item_id="{{ $item->id }}"></sku-discount>
                </div>


                <div class="from-group">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <small class="me-2"><i class="fas fa-save"></i></small>
                        <span>Save</span>
                    </button>
                </div>
            </div>

        </div>


    </form>
</div>

@endsection