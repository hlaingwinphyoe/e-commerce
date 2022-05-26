@extends('layouts.admin')

@section('title', 'Discountypes')

@section('classes', 'admin admin-discountypes admin-discountypes-edit')

@section('content-header')
<x-admin.content-header :navs="['discountypes', 'edit']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 mr-2">Discountype</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.discountypes.update', $discountype->id) }}" method="post">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="form-group">
                    <label for="">
                        Name
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">အမည်ထည့်ပါ။ တူ၍မရပါ။</small>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ old('name') ?? $discountype->name }}">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Amount <span class="text-danger">**</span></label>
                    <small class="help-text text-muted">Discount Amt ထည့်ပါ။ မဖြစ်မနေထည့်ပေးပါ။</small>
                    <div class="input-group mb-2 mr-sm-2">
                        <input type="text" name="amt" class="form-control form-control-sm" placeholder="Amt" value="{{ old('amt') ?? $discountype->amt }}">
                        <div class="input-group-text bg-white">
                            <select name="status" class="form-select border-0">
                                @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $discountype->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('amt')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="form-group start-date-container">
                            <label for="">Start Date</label>
                            <small class="help-text text-muted">Discount စတင်မည့်ရက်၊ မထည့်လည်းရပါသည်။</small>
                            <input type="date" name="start_date" id="start-date" class="form-control form-control-sm date-picker" placeholder="Start Date" value="{{ old('start_date') ?? $discountype->start_date }}">
                            @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="form-group end-date-container">
                            <label for="">End Date</label>
                            <small class="help-text text-muted">Discount ပြီးဆုံးမည့်ရက်၊ မထည့်လည်းရပါသည်။</small>
                            <input type="date" name="end_date" id="end-date" class="form-control form-control-sm date-picker" placeholder="End Date" value="{{ old('end_date') ?? $discountype->end_date }}">
                            @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Description</label>
                    <small class="help-text text-muted">ဖော်ပြချက်ထည့်ပါ။ မထည့်လည်းရပါသည်။</small>
                    <textarea name="desc" class="form-control form-control-sm" rows="3" placeholder="Description">{{ old('desc') ?? $discountype->desc }}</textarea>
                </div>
            </div>

            <div class="col-md-4 mb-2">

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
