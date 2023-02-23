@extends('layouts.admin')

@section('title', 'Delifees')

@section('classes', 'admin admin-delifees admin-delifees-create')

@section('content-header')
<x-admin.content-header :navs="['delifees', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <a href="{{ route('admin.delifees.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Deli Fees</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.delifees.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="py-3">
                    <div class="form-group">
                        <label for="">
                            Amount
                            <span class="text-danger">**</span>
                        </label>
                        <small class="help-text text-muted">Amount ထည့်ပါ။</small>
                        <input type="text" name="amt" class="form-control form-control-sm" placeholder="Amt" value="{{ old('amt') }}">
                        @error('amt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Description</label>
                        <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                        <textarea name="desc" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('desc') }}</textarea>
                    </div>

                    <div class="form-group">
                        <ul class="nav flex-column">
                            @foreach($regions as $region)
                            <li class="nav-item border-bottom pb-3">
                                <span class="nav-link pl-0">{{ $region->name }}</span>
                                <div class="d-flex flex-wrap">
                                    @foreach($region->enabled_townships()->whereDoesntHave('delifees')->get() as $township)
                                    <div class="form-check w-responsive-25">
                                        <input type="checkbox" name="townships[]" value="{{ $township->id }}" class="form-check-input" id="township-{{ $township->id }}">
                                        <label for="township-{{ $township->id }}" class="form-check-label">{{ $township->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="from-group">
            <button delifee="submit" class="btn btn-sm btn-secondary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Save</span>
            </button>
        </div>
    </form>



</div>

@endsection
