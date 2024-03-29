@extends('layouts.admin')

@section('title', 'Item')

@section('classes', 'admin admin-item item-index')

@section('content')

<x-admin.search-box url="{{ route('admin.items.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.item')}}</h4>
    <span class="text-muted form-text">( Showing {{ $items->count() }} of total {{ $items->total() }} records )</span>
</div>

@include('components.admin.message')

<?php $item_count =  \App\Models\Item::withTrashed()->count(); ?>

{{-- @if($item_count >= config('app.max_data'))
<div class="">
    <p class="alert alert-danger py-1">You have already <span class="fw-bold h5">{{ config('app.max_data') }}</span> items (Including in Trash and Disabled) and can't add anymore.</p>
</div>
@endif --}}


<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
if (request('type')) {
    $query .= $query ? '&' : '?';
    $query .= 'type=' . request('type');
}
if (request('brand')) {
    $query .= $query ? '&' : '?';
    $query .= 'brand=' . request('brand');
}
?>

<div class="border bg-white rounded px-2 py-4">
    <div class="d-flex">
        <!-- filter -->
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-item')) <div class="me-2">
                <a href="{{ route('admin.items.create') }}" class="btn btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
                @endif
        </div>


        <form action="{{ route('admin.items.index') }}" method="get" class="d-flex">
            <div class="me-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Select Status</option>
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </div>
            <div class="me-2">
                <button id="apply-actions" class="btn btn-secondary" data-route="item">
                    <i class="fa fa-check me-2"></i>
                    <span>Apply</span>
                </button>
            </div>
        </form>

        <form action="{{ route('admin.items.index') }}" class="d-flex responsive-flex">
            <input type="hidden" name="disabled" value="{{ request('disabled') }}">

            <div class="form-group me-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">Choose Category</option>
                    @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $type->id == request()->type? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group me-2">
                <select name="brand" class="form-select form-select-sm">
                    <option value="">Choose Brand</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->slug }}" {{ $brand->id == request()->brand? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-outline-secondary me-2">Filter</button>
                <a href="{{ route('admin.items.index') }}" class="btn btn-danger">
                    <small><i class="fa fa-redo m-0"></i></small>
                </a>
            </div>
        </form>
    </div>

</div>

<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th width="250px">Name</th>
                <th>Skus</th>
                <th>Photo</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Discount</th>
                <th><i class="fa fa-ellipsis-vertical"></i></th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr id="tr-{{ $item->id }}" class="align-middle">
                <td class="">{{ $item->name }}</td>
                <td>
                    @if($item->skus->count() > 0)
                    <a href="{{ route('admin.items.show', $item->id) }}" class="p-2 text-decoration-none" style="letter-spacing: 0.6px">
                        @foreach($item->skus as $sku)
                            <span>
                                {{ $sku->code ?? 'Get Code' }}
                            </span>
                        @endforeach
                    </a>
                    @else
                    <span>{{ $item->skus->count() }}</span>
                    @endif
                </td>
                <td>
                    @php
                        $img = $item->medias()->first();
                    @endphp
                    @if ($img)
                        <img src="{{ asset('storage/thumbnail/'.$img->slug) }}" width="65" alt="item_image">
                        @else
                        <img src="{{ asset('images/featured/default.png') }}"  width="50" alt="">
                    @endif
                </td>
                <td>{{ $item->type() ? $item->type()->name : '-' }}</td>
                <td>
                    <a href="#add-stock-modal-{{ $item->id }}" class="badge stock bg-secondary text-decoration-none p-2" data-bs-toggle="modal">
                        <span class="">{{ $item->getStock() }}</span>
                    </a>
                    <stock :item="{{ $item }}" :suppliers="{{ $suppliers }}"></stock>
                </td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->discount }}</td>
                <td>
                    @if(auth()->user()->role->hasPermission('edit-item') && !$item->trashed())
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="me-2">
                            <span><i class="fa fa-pencil-alt text-warning"></i></span>
                        </a>
                    @endif
                    @if(auth()->user()->role->hasPermission('delete-item') && !$item->trashed())
                        <a href="#delete-modal-{{ $item->id }}" class="action-btn me-2 text-danger" data-bs-toggle="modal">
                            <span><i class="fas fa-trash"></i></span>
                        </a>
                        <x-admin.delete id="{{ $item->id }}" url="{{ route('admin.items.destroy', $item->id) }}"></x-admin.delete>
                    @endif
                    @if($item->trashed() && auth()->user()->role->hasPermission('restore-item'))
                        <a href="#restore-modal-{{ $item->id }}" class="action-btn me-2" data-bs-toggle="modal">
                            <small><i class="fas fa-sync-alt"></i></small>
                        </a>
                        <x-admin.restore id="{{ $item->id }}" url="{{ route('admin.items.restore', $item->id) }}"></x-admin.restore>
                    @endif
                    @if($item->trashed() && auth()->user()->role->hasPermission('permenent-delete-item'))
                        <a href="#force-delete-modal-{{ $item->id }}" class="action-btn me-2 text-danger" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.force-delete id="{{ $item->id }}" url="{{ route('admin.items.delete', $item->id) }}"></x-admin.force-delete>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">There is no result for you.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="paginate">
    {{ $items->appends(request()->query->all())->links('components.pagination') }}
</div>
</div>
@endsection
