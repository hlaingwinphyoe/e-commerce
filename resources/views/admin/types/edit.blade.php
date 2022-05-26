@extends('layouts.admin')

@section('title', 'Category')

@section('classes', 'admin admin-types admin-types-edit')

@section('content-header')
<x-admin.content-header :navs="['types', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 mr-2">Category</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.types.update', $type->id) }}" method="post">
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
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') ?? $type->name }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                   <div class="form-group d-none">
                        <label for="">
                            Main Category
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Main Category ရွေးပါ။ ဖဖြစ်မနေထည့်ပါ။</small>
                        <select name="maintype" class="form-select">
                            <option value="">Select Main Category</option>
                            @foreach($maintypes as $maintype)
                            <option value="{{ $maintype->id }}" {{ $type->hasMaintype($maintype->id) ? 'selected' : '' }}>{{ $maintype->name }}</option>
                            @endforeach
                        </select>
                        @error('maintype')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="">Sub Category</label>
                        <small class="help-text">အထက်တွင် level တဆင့် ရှိပါက ထည့်ပါ။</small>
                        <select name="parent_id" class="form-select">
                            <option value="">Select Sub Category</option>
                            @foreach($types as $t)
                            <option value="{{ $t->id }}" {{ $type->hasParent($t->id) ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="desc" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('desc') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-2">
                <div class="px-4 py-3 shadow">
                    <h4 class="text-secondary">Featured Image</h4>
                    <media-upload :images="{{ $type->medias()->pluck('id') }}" type="category" priority="check"></media-upload>
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
