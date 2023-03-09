@extends('layouts.admin')

@section('title', 'Generals Edit')

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.generals.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">General Form</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>

    <form action="{{ route('admin.generals.update',$general->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">
                                Date
                                <span class="text-danger">**</span>
                            </label>
                            <input type="date" name="date" class="form-control form-control-sm" placeholder="Date" value="{{ old('name',$general->date) }}">
                            @error('date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Description</label>
                            <textarea name="desc" id="" cols="30" rows="7" class="form-control">{{ old('desc',$general->desc) }}</textarea>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="general" value="general" {{ old('type',$general->type) == 'general' ? 'checked' : '' }}>
                            <label class="form-check-label" for="general">General Use</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="return" value="return" {{ old('type',$general->type) == 'return' ? 'checked' : '' }}>
                            <label class="form-check-label" for="return">Waste / Damage / Expired</label>
                        </div>

                        <div class="from-group mt-3">
                            <button type="submit" class="btn btn-sm btn-dark">
                                <small class="me-2"><i class="fas fa-save"></i></small>
                                <span>Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($general)
    <general-index :general="{{ $general }}" type="{{ $general->type }}" :skus="{{ $general->skus }}"></general-index>
    @else
    <general-index general="" type="general" :skus="{{ $general->skus }}"></general-index>
    @endif

</div>

@endsection
