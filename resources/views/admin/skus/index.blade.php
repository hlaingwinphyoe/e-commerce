@extends('layouts.admin')

@section('title', 'Skus')

@section('classes', 'admin admin-skus admin-skus-index')

@section('content')

<x-admin.search-box url="{{ route('admin.skus.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-2">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.stocks')}}</h4>
        <span class="text-muted form-text">( Showing {{ $skus->count() }} of total {{ $skus->total() }} records )</span>
    </div>
    <p class="mb-4 bg-light small px-1 py-2">အနီရောင်ပြထားသော items များမှာ ရှိရမည့်လက်ကျန်ပမာဏထက် နည်းနေသော / stock ထပ်ဖြည့်ရန်လိုအပ်သော items များဖြစ်ပါသည်။</p>

    <div class="d-flex flex-wrap">
        <form action="{{ route('admin.skus.index') }}" class="d-flex responsive-flex">
            <div class="form-group me-2">
                <select name="type" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <select name="brand" class="form-select">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <select name="status" class="form-select">
                    <option value="">Select Status</option>
                    <option value="instock" {{ request()->status == 'instock' ? 'selected' : '' }}>Instock</option>
                    <option value="outofstock" {{ request()->status == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.skus.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

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

    <nav class="d-flex align-items-center mb-2">
        <div class="dropdown">
            <a class="text-dark dropdown-toggle" href="#" data-bs-toggle="dropdown">
                Sort By
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.skus.index') }}{{ $query }}{{ $query ? '&sort=asc' : '?sort=asc' }}">A-Z</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.skus.index') }}{{ $query }}{{ $query ? '&sort=desc' : '?sort=desc' }}">Z-A</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.skus.index') }}{{ $query }}{{ $query ? '&sort=max_stock' : '?sort=max_stock' }}">Max Stock</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.skus.index') }}{{ $query }}{{ $query ? '&sort=min_stock' : '?sort=min_stock' }}">Min Stock</a></li>
            </ul>
        </div>
    </nav>

    @include('components.admin.message')

    @include('components.admin.errors')

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Cost</th>
                    <th>Current Stock</th>
                    <th>Min Stock</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($skus as $sku)
                <tr id="tr-{{ $sku->id }}">
                    <td><input type="checkbox" id="check-{{ $sku->id }}" value="{{ $sku->id }}"></td>
                    <td>
                        <?php $item = $sku->item()->withTrashed()->first(); ?>
                        <p class="mb-0 {{ $sku->stock < $sku->min_stock ? 'text-danger' : '' }}">{{ $item ? $item->name : '-' }}<span class="text-primary">{{ $sku->data ? ' (' . $sku->data . ')' : '' }}</span></p>
                    </td>
                    <td>
                        @if ($sku->buy_price >= $sku->price)
                        <span class="text-danger">{{ number_format($sku->price) }}</span>
                        <div><a href="{{ route('admin.items.edit', $sku->item_id) }}" class="small">ရောင်းဈေးပြင်မည်</a></div>
                        @else
                        <span>{{ number_format($sku->price) }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($sku->buy_price) }}</td>
                    <td><span title="Stock" class="bg-primary px-2 rounded text-white">{{ $sku->stock }}</span></td>
                    <td>{{ $sku->min_stock }}</td>
                    <td>
                        <a href="#add-stock-{{ $sku->id }}" class="btn btn-sm btn-outline-primary me-1 mb-1" data-bs-toggle="modal"><i class="fa fa-plus"></i></a>
                        @include('admin.skus.add-stock')

                        <a href="{{ route('admin.skus.reset-stock', $sku->id) }}" class="btn btn-sm btn-danger me-1 mb-1">Reset</a>

                        <a href="{{ route('admin.skus.show', $sku->id) }}" class="btn btn-sm btn-outline-secondary me-1 mb-1">Details</a>
                        @if(auth()->user()->role->hasPermission('create-item'))
                        <button type="button" class="btn btn-sm btn-info me-1 mb-1" data-bs-toggle="modal" data-bs-target="#add-waste-{{ $sku->id }}">Add Waste</button>
                        @include('admin.skus.waste')
                        @endif                       

                        @if(auth()->user()->role->type == 'Developer')
                        <form action="{{ route('admin.skus.update', $sku->id) }}" method="post" class="d-inline-block d-none">
                            @csrf
                            @method('patch')
                            <button type="submit" class="btn btn-sm btn-dark"><i class="fa fa-check"></i></button>
                        </form>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-item'))
                        <a href="#delete-modal-{{ $sku->id }}" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal">
                            <span><i class="fas fa-trash"></i></span>
                        </a>
                        <x-admin.delete id="{{ $sku->id }}" url="{{ route('admin.skus.destroy', $sku->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $skus->appends(request()->query->all())->links('components.pagination') }}
    </div>

</div>

@endsection