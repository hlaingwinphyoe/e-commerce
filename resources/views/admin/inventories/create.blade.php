@extends('layouts.admin')

@section('title', 'Purchase')

@section('classes', 'admin admin-inventory admin-inventory-create')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">{{ __('menu.purchase') }}</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.inventories.store') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-3">Supplier Information</h5>

                    <div class="d-flex flex-wrap pb-3">

                        <div class="form-group me-2 w-sm-100">
                            <label for="">
                                Name
                            </label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">Choose Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group me-2 w-sm-100">
                            <label for="">
                                Date
                            </label>
                            <input type="date" name="date" class="form-control form-control-sm">
                            @error('date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group me-2 w-sm-100 align-self-end">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <small class="me-2"><i class="fas fa-save"></i></small>
                                <span>Add Items</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

@endsection