@extends('layouts.admin')

@section('title', 'Coupons')

@section('classes', 'admin admin-coupons admin-coupons-index')

@section('content')

<x-admin.search-box url="{{ route('admin.coupons.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.coupon')}}</h3>
</div>

@include('components.admin.message')

@include('components.admin.errors')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $coupons->count() }}</span> of total <span class="">{{ $coupons->total() }}</span></p>

    <div class="d-flex responsive-flex">
        <a href="#import-example" class="form-text d-none" data-bs-toggle="modal">Show Import Example</a>
        @include('admin.coupons.import-example')
    </div>

    <div class="d-flex mb-3">
        <div class="d-flex flex-warp mb-2">
            @if(auth()->user()->role->hasPermission('create-coupon'))
            <div class="me-2 mb-1">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            <div class="me-2 mb-1">
                <a href="#generate-coupon-form" data-bs-toggle="modal" class="btn btn-outline-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Genearate</span>
                </a>
                <x-generate-coupon></x-generate-coupon>
            </div>
            @endif

            <div class="me-2 mb-1">
                <form action="{{ route('admin.coupons.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-btn-wrapper d-none">
                        <button class="btn btn-danger">
                            <small class="me-1"><i class="far fa-file-excel"></i></small>
                            <span>Import</span>
                        </button>
                        <input type="file" name="files" id="excel-input">
                    </div>
                </form>
            </div>

            <form action="{{ route('admin.coupons.index') }}" class="d-flex responsive-flex">
                <div class="form-group me-2">
                    <select name="type_id" class="form-select">
                        <option value="">Select Type</option>
                        @foreach($maintypes as $maintype)
                        <option value="{{ $maintype->id }}" {{ request('type_id') == $maintype->id ? 'selected' : '' }}>{{ $maintype->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group me-2">
                    <select name="type" class="form-select">
                        <option value="">Select Fixed or Percent</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Percent</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-primary me-2">Filter</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">
                        <small><i class="fa fa-redo"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Code</th>
                    <th>Amount</th>
                    <th>Expired</th>
                    <th>By</th>
                    <th>Active</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr id="tr-{{ $coupon->id }}">
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type == 'fixed' ? $coupon->value . 'MMK' : $coupon->percent_off . '%' }}</td>
                    <td>{{ $coupon->expired ? Carbon\Carbon::parse($coupon->expired)->format('d M, Y') : ' - ' }}</td>                                        <td>{{ $coupon->user ? $coupon->user->name : ' - ' }}</td>
                    <td>
                        <a href="{{ route('admin.coupons.index') }}?is_used={{ $coupon->is_used ? 'used' : 'active' }}" class="badge badge-{{ $coupon->is_used ? 'secondary' : 'success' }}">{{ $coupon->is_used ? 'Used' : 'Active' }}</a>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-coupon'))
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-coupon'))
                        <a href="#delete-modal-{{ $coupon->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $coupon->id }}" url="{{ route('admin.coupons.destroy', $coupon->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $coupons->appends(request()->query->all())->links('components.pagination') }}
    </div>

</div>
@endsection
