@extends('layouts.admin')

@section('title', 'Brands')

@section('classes', 'admin admin-brands admin-brands-index')

@section('content')

<x-admin.search-box url="{{ route('admin.brands.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.brand')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $brands->count() }}</span> of total <span class="">{{ $brands->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-brand'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.brands.create') }}" class="btn btn-secondary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

        </div>
    </div>

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
                    <th>Name</th>
                    <th>Items</th>
                    <th>Link</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr id="tr-{{ $brand->id }}">
                    <td>{{ $brand->name }}</td>
                    <td>
                        @if($brand->items()->count())
                        <a href="{{ route('admin.items.index') }}?brand={{ $brand->slug }}" class="badge bg-primary">
                            {{ $brand->items()->count() }}
                        </a>
                        @endif
                    </td>
                    <td>
                        <span class="me-2 copy-text">{{ route('category') }}?brand={{ $brand->slug }}</span>
                        <a href="#" class="copy-button text-success">
                            <i class="fa fa-copy"></i>
                        </a>
                        <small class="text-muted d-none copied-text">Copied</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-brand'))
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-brand'))
                        <a href="#delete-modal-{{ $brand->id }}" data-bs-toggle="modal" class="text-danger">
                            <i class="fas fa-trash"></i>
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
@endsection
