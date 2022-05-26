@extends('layouts.admin')

@section('title', 'Brands')

@section('classes', 'admin admin-brands admin-brands-index')

@section('content')

<x-admin.search-box url="{{ route('admin.brands.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.brand')}}</h4>
        <span class="text-muted form-text">( Showing {{ $brands->count() }} of total {{ $brands->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-brand'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-brand'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
                <option value="disabled">Disabled</option>
                <option value="enabled">Enabled</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="brand">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif
    </div>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4 d-none">
        <li class="nav-item">
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.brands.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell-slash"></i>
                Disabled
            </a>
        </li>
    </ul>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Name</th>
                    <th>Items</th>
                    <th>Link</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr id="tr-{{ $brand->id }}">
                    <td><input type="checkbox" id="check-{{ $brand->id }}" value="{{ $brand->id }}"></td>
                    <td>{{ $brand->name }}</td>
                    <td>
                        @if($brand->items()->count())
                        <a href="{{ route('admin.items.index') }}?brand={{ $brand->slug }}" class="badge badge-primary">
                            {{ $brand->items()->count() }}
                        </a>
                        @else
                        <span>{{ $brand->items()->count() }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="me-2 copy-text">{{ route('category') }}?brand={{ $brand->slug }}</span>
                        <a href="#" class="copy-button btn btn-sm btn-dark">
                            <small><i class="fa fa-copy"></i></small>
                        </a>
                        <small class="text-muted d-none copied-text">Copied</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-brand'))
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-brand'))
                        <a href="#delete-modal-{{ $brand->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $brand->id }}" url="{{ route('admin.brands.destroy', $brand->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $brands->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@if(auth()->user()->role->hasPermission('delete-brand'))
<x-admin.delete-all url="/wapi/brands"></x-admin.delete-all>
@endif
@endsection
