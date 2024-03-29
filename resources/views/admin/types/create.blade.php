@extends('layouts.admin')

@section('title', 'Category')

@section('classes', 'admin admin-types admin-types-create')

@section('content-header')
<x-admin.content-header :navs="['types', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.types.index') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Category</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.types.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
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
                        <label for="">Main Category</label>
                        <small class="help-text">အထက်တွင် level တဆင့် ရှိပါက ထည့်ပါ။</small>
                        <select name="parent_id" class="form-select">
                            <option value="">Select Main Category</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('parent_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                <div class="shadow px-4 py-3">
                    <h5 class="text-secondary">Featured Image</h5>
                    <media-upload :images="[]" type="category" priority="check"></media-upload>
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
