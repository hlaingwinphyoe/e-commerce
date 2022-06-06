@extends('layouts.admin')

@section('title', 'Gifts')

@section('classes', 'admin admin-gifts admin-gifts-index')

@section('content')

<x-admin.search-box url="{{ route('admin.gifts.index') }}"></x-admin.search-box>

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.gifts')}}</h2>
</div>

@include('components.admin.message')

@include('components.admin.errors')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $gifts->count() }}</span> of total <span class="">{{ $gifts->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-gift'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.gifts.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            @if(auth()->user()->role->hasPermission('access-gift-log'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.gift-logs.index') }}" class="btn btn-sm btn-outline-primary">
                    <span>Gift Log</span>
                </a>
            </div>
            @endif

            @if(auth()->user()->role->hasPermission('access-gift-inventory'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.gift-inventories.index') }}" class="btn btn-sm btn-secondary">
                    <span>Inventory</span>
                </a>
            </div>
            @endif
        </div>
    </div>

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
					<th width="200px"></th>
                    <th>Name</th>
                    <th>Point</th>
                    <th>Stock</th>
                    <th>Inventory</th>
					<th>Given</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($gifts as $gift)
                <tr id="tr-{{ $gift->id }}">
					<td>
						<img src="{{ $gift->thumbnail }}" alt="{{ $gift->name }}" style="max-height: 50px;">
					</td>
                    <td>{{ $gift->name }}</td>
					<td>{{ $gift->points }}</td>
					<td>{{ $gift->stock }}</td>
                    <td><a href="{{ route('admin.gifts.show', $gift->id) }}" class="badge badge-success">{{ $gift->getTotalInventoriesCount() }}</a></td>
					<?php 
						$user_gifts_count = $gift->userGifts()->whereHas('status', function($q) {
							$q->where('slug', '!=', 'cancel');
						})->count();
					?>
					<td>
						@if($user_gifts_count)
						<a href="{{ route('admin.gift-logs.index') }}?q={{ $gift->name }}" class="badge badge-danger">
							{{ $user_gifts_count }}
						</a>
						@else
						<span>{{ $user_gifts_count }}</span>
						@endif
					</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('create-gift-inventory'))
                        <a href="{{ route('admin.gift-inventories.create') }}?id={{ $gift->id }}" class="btn btn-sm btn-info me-2 mb-1">
                            <small><i class="fa fa-plus"></i></small>
                            <span>Stock</span>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('edit-gift'))
                        <a href="{{ route('admin.gifts.edit', $gift->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif                        

                        @if(auth()->user()->role->hasPermission('delete-gift'))
                        <a href="#delete-modal-{{ $gift->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $gift->id }}" url="{{ route('admin.gifts.destroy', $gift->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $gifts->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
