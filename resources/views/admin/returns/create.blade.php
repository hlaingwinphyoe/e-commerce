@extends('layouts.admin')

@section('title', 'Return')

@section('classes', 'admin admin-returns admin-returns-create')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.returns.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Return</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>


    <form action="{{ route('admin.returns.store') }}" method="post">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="bg-white shadow rounded py-3 px-2 mb-4">
                    <h5 class="text-primary mb-3">Return Form</h5>

                    <div class="d-flex">
                        <div class="form-group me-2 w-sm-100">
                            <label for="">Order</label>
                            <search-or-create url="orders" name="order_id"></search-or-create>
                            @error('order_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group me-2 w-sm-100">
                            <label for="">Date</label>
                            <input type="date" name="date" placeholder="Date" class="form-control form-control-sm">
                            @error('date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="form-group">
            <button type="submit" class="btn btn-sm btn-primary">
                <small class="me-2"><i class="fas fa-save"></i></small>
                <span>Add Items</span>
            </button>
        </div>
    </form>

</div>

@endsection
