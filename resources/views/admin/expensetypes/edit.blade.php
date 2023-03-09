@extends('layouts.admin')

@section('title', 'Create Expense Type')

@section('classes', 'admin admin-types admin-types-create')

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.expensetypes.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Expense Type</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.expensetypes.update',$expensetype->id) }}" method="post">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name',$expensetype->name) }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="desc" class="form-control form-control-sm" rows="5" placeholder="Description">{{ old('desc',$expensetype->desc) }}</textarea>
                    </div>
                    </div>
            </div>
        </div>

        <div class="from-group">
            <button type="submit" class="btn btn-sm btn-dark">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Update</span>
            </button>
        </div>
    </form>

</div>

@endsection
