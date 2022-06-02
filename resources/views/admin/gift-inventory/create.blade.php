@extends('layouts.admin')

@section('title', 'Gift Inventory')

@section('classes', 'admin admin-gifts admin-gifts-create')

@section('content-header')
<x-admin.content-header :navs="['gift-inventories', 'create']"></x-admin.content-header>
@endsection

@section('content')

@include('components.admin.errors')

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">Gift Inventory</h4>
        <span class="text-muted form-text">( Create )</span>
    </div>

    <gift-inventory gift_id="{{ $gift_id }}" :inventories="{{ $inventories }}"></gift-inventory>
</div>

@endsection