@extends('layouts.admin')

@section('title', 'Gift')

@section('classes', 'admin admin-gifts admin-gifts-create')

@section('content-header')
<x-admin.content-header :navs="['gifts', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Gift</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.gifts.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Name
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">အမည်ထည့်ပါ။</small>
                        <input brand="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">
                            Point
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Point ထည့်ပေးပါ။ မဖြစ်မနေထည့်ပေးပါ။</small>
                        <input brand="text" name="points" class="form-control form-control-sm" placeholder="Point" value="{{ old('points') }}">
                        @error('points')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                </div>
            </div>

            <div class="col-md-4 mb-2">
                <div class="bg-sidebar px-4 py-3">
                    <h5 class="text-secondary">Featured Image</h5>
                    <media-upload :images="[]" type="gift" priority="check"></media-upload>
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