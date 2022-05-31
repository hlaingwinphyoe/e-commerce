@extends('layouts.admin')

@section('title', 'Deliveries')

@section('classes', 'admin admin-deliveries admin-deliveries-index')

@section('content')

<x-admin.search-box url="{{ route('admin.deliveries.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deliveries')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $deliveries->count() }}</span> of total <span class="">{{ $deliveries->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-delivery'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.deliveries.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <ul class="nav site-nav-tabs mb-4 d-none">
        <li class="nav-item">
            <a href="{{ route('admin.deliveries.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.deliveries.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Phone</th>
                    <th>Address</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr id="tr-{{ $delivery->id }}">
                    <td>{{ $delivery->name }}</td>
                    <td>{{$delivery->phone}}</td>
                    <td>{{$delivery->address}}</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('access-delivery'))
                        <a href="{{ route('admin.deliveries.show', $delivery->id) }}" class="btn btn-sm btn-info me-2 mb-1">
                            <small><i class="fa fa-eye"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('edit-delivery'))
                        <a href="{{ route('admin.deliveries.edit', $delivery->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-delivery'))
                        <a href="#delete-modal-{{ $delivery->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $delivery->id }}" url="{{ route('admin.deliveries.destroy', $delivery->id) }}"></x-admin.delete>
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
        {{ $deliveries->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
