@extends('layouts.admin')

@section('title', 'Faqs')

@section('classes', 'admin admin-faqs admin-faqs-edit')

@section('content-header')
<x-admin.content-header :navs="['faqs', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">FAQ</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="form-group">
                    <label for="">
                        Title
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">အမည်ထည့်ပါ။</small>
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="title" value="{{ old('title') ?? $faq->title }}">
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">
                        FAQ Type
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">အမည်ထည့်ပါ။</small>
                    <select name="faq_type" class="form-select">
                        @foreach($faq_types as $type)
                        <option value="{{ $type->id }}" {{ $faq->faq_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <span class="text-danger">**</span>
                    <small class="help-text text-muted">မေးခွန်းအတွက် အဖြေထည့်ပေးပါ။</small>
                    <textarea name="description" class="text-editor form-control form-control-sm" rows="3" placeholder="Description">{{ old('description') ?? $faq->desc }}</textarea>
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 mb-2">

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
