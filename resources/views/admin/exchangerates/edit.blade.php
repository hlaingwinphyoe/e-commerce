@extends('layouts.admin')

@section('title', 'Exchangerates')

@section('classes', 'admin admin-exchangerates admin-exchangerates-create')

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.exchangerates.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Exchangerate</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>


    <form action="{{ route('admin.exchangerates.update',$exchangerate->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="form-group">
                    <label for="">
                        Exchange Rate
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">Exchangerate ထည့်လျှင် divisionrate ထည့်ရန်မလိုပါ။ (Eg.211.86441 for Yuan)</small>
                    <input type="text" name="exchange_rate" class="form-control form-control-sm" placeholder="Exchange Rate" value="{{ old('exchange_rate',$exchangerate->mmk) }}">
                    @error('exchange_rate')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">
                        Division Rate
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">Divisionrate ထည့်လျှင် exchangerate ထည့်ရန်မလိုပါ။ (Eg. 0.00472 for Yuan)</small>
                    <input type="text" name="division_rate" class="form-control form-control-sm" placeholder="Division Rate" value="{{ old('division_rate',$exchangerate->rate) }}">
                    @error('division_rate')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">
                        Currency
                        <span class="text-danger">**</span>
                    </label>
                    <small class="help-text text-muted">Currency ရွေးပါ။ မဖြစ်မနေထည့်ပေးပါ။</small>
                    <select name="currency" class="form-select">
                        @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ old('currency',$exchangerate->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                        @endforeach
                    </select>
                    @error('currency')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4 mb-2">

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
