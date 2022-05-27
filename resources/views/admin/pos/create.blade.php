@extends('layouts.pos')

@section('title', 'POS')

@section('classes', 'admin admin-pos admin-pos-create')

@section('content')

<div class="mb-2 px-2 pt-2">
    <a href="{{ route('admin.pos.index') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-arrow-left"></i>
        <span>Return to Sale Lists</span>
    </a>
</div>

<pos-index :order="{{ $order }}" :skus="{{ $skus }}" :statuses="{{ $statuses }}"></pos-index>

@endsection