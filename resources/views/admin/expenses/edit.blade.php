@extends('layouts.admin')

@section('title', 'Expenses')

@section('classes', 'admin admin-expenses admin-expenses-create')

@section('content')

<div>

    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2"><i class="fa fa-arrow-left"></i></a>
        <h4 class="page-title mb-0 me-2">Expenses</h4>
        <span class="text-muted form-text">( Edit )</span>
    </div>

    <form action="{{ route('admin.expenses.update', $expense->id) }}" method="post">
        @csrf
        @method('patch')


        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow">
                    <div class="card-body">

                        <div class="form-group mb-3">
                            <label for="">Type</label>
                            <select name="type_id" class="form-select">
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ $expense->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                         <div class="form-group mb-3">
                            <label for="">Desc</label>
                            <input type="text" name="name" class="form-control" value="{{ $expense->name }}">
                        </div>

                        <div class="from-group mb-3">
                            <label for="">Supplier/Shop</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">Select Supplier/Shop</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $expense->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Amount</label>
                            <input type="text" name="amount" class="form-control" value="{{ $expense->amount }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Reference Code (Optional)</label>
                            <input type="text" name="reference_id" class="form-control" value="{{ $expense->reference_id }}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-save me-2"></i>Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>

</div>


</div>

@endsection
