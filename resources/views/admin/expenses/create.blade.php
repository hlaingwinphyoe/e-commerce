@extends('layouts.admin')

@section('title', 'Expenses')

@section('classes', 'admin admin-expenses admin-expenses-create')

@section('content')

<div>

    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2"><i class="fa fa-arrow-left"></i></a>
        <h4 class="page-title mb-0 me-2">Expenses</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>

    <expense-index :types="{{ $types }}" :suppliers="{{ $suppliers }}"></expense-index>

</div>


</div>

@endsection
