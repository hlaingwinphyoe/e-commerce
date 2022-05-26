@extends('layouts.admin')

@section('title', 'Purchases')

@section('classes', 'admin admin-inventories admin-inventories-create')

@section('content-header')
<x-admin.content-header :navs="['inventories', 'create']"></x-admin.content-header>
@endsection

@section('content')

<div>

    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Purchases</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <div class="row">
        <div class="col-md-5">
            <form action="{{ route('admin.inventories.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="">Supplier ရှိလျှင်ထည့်ပါ</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Choose Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Date ရွေးပါ</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">

                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary"><span class="me-2"><i class="fa fa-save"></i></span> Create</button>
                </div>
            </form>
        </div>
    </div>


</div>


</div>

@endsection