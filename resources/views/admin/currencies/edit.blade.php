@extends('layouts.admin')

@section('title', 'Currencies')

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align--items-center">
        <a href="{{ route('admin.currencies.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Currency</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.currencies.update',$currency->id) }}" method="post">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="form-group">
                    <label for="">
                        Currency
                        <span class="text-danger">**</span>
                    </label>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Currency" value="{{ old('name',$currency->name) }}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
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
