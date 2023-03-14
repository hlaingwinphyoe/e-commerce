@extends('layouts.admin')

@section('title', 'Total Expenses')

@section('classes', 'admin admin-total-expenses')

@section('content')

<div>
    <h2 class="page-title text-capitalize">Total Expenses</h2>
</div>

<div class="py-4 row">
    <div class="col-md-5">
        <div class="border shadow px-3 py-4 bg-danger-subtle rounded">
            <h5 class="mb-0"> {{ $message }} <i class="fa-solid fa-face-smile-beam"></i> </h5>
        </div>
    </div>
</div>


@endsection
