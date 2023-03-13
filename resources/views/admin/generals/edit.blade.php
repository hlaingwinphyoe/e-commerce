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

    @if($general)
        <general-index :general="{{ $general }}" type="{{ $general->type }}" :skus="{{ $general->skus }}"></general-index>
        @else
        <general-index general="" type="general" :skus="{{ $general->skus }}"></general-index>
    @endif

</div>

@endsection
