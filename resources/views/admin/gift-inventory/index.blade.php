@extends('layouts.admin')

@section('title', 'Gifts Inventory')

@section('classes', 'admin admin-gifts-inventory admin-gifts-inventory-index')

@section('content')

<x-admin.search-box url="{{ route('admin.gifts.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">Gifts Inventory</h4>
        <span class="text-muted form-text">( Showing {{ $gift_inventories->count() }} of total {{ $gift_inventories->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('access-gift'))
         <div class="me-2 mb-3">
            <a href="{{ route('admin.gifts.index') }}" class="btn btn-sm btn-outline-primary mb-4">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Gifts</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('create-gift-inventory'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.gift-inventories.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
       
        @endif

    </div>

    @include('components.admin.message')

    @include('components.admin.errors')

    <ul class="nav site-nav-tabs mb-4 d-none">
        <li class="nav-item">
            <a href="{{ route('admin.gifts.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.gifts.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Qty</th>
                    <th>By</th>
                    <th>Date</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($gift_inventories as $inventory)
                <tr id="tr-{{ $inventory->id }}">
                    <td>{{ $inventory->gift->name }}</td>
                    <td>{{ $inventory->qty }}</td>
                    <td>{{ $inventory->user->name }}</td>
                    <td>{{ $inventory->date ? \Carbon\Carbon::parse($inventory->date)->format('d M, Y') : $inventory->created_at->format('d M, Y') }}</td>
                    <td>
                        @if(!$inventory->is_published && auth()->user()->role->hasPermission('edit-gift-inventory'))
                        <form action="{{ route('admin.gift-inventories.close', $inventory->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Close</button>
                        </form>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-gift-inventory'))
                        <a href="#delete-modal-{{ $inventory->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $inventory->id }}" url="{{ route('admin.gift-inventories.destroy', $inventory->id) }}"></x-admin.delete>
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
        {{ $gift_inventories->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>

@endsection