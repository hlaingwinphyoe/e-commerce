@extends('layouts.admin')

@section('title', 'Category')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.types.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.category')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $types->count() }}</span> of total <span class="">{{ $types->total() }}</span></p>

    <p class="small text-secondary py-2">Priority စီလျှင် ငယ်ရာမှကြီးရာသို့ စီထားပါသည်။ (Eg. 0,1,2,3,4,... )</p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-type'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.types.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.types.index') }}" class="d-flex responsive-flex d-none">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">
                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                    <a href="{{ route('admin.types.index') }}" class="btn btn-sm btn-primary">
                        <small><i class="fa fa-redo"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav site-nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.types.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.types.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Parent</th>
                    <th>Items</th>
                    <th>Priority</th>
                    <th>Link</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr id="tr-{{ $type->id }}">
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->parent_type ? $type->parent_type->name : ' - ' }}</td>
                    <td>
                        @if($type->items()->count())
                        <a href="{{ route('admin.items.index') }}?type={{ $type->slug }}" class="badge bg-primary">
                            {{ $type->items()->count() }}
                        </a>
                        @else
                        <span>{{ $type->items()->count() }}</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.types.change-priority', $type->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div>
                                <input type="text" name="priority" placeholder="Priority" class="form-control form-control-sm role-select" value="{{ $type->priority }}" style="max-width: 85px">
                            </div>
                        </form>
                    </td>
                    <td>
                        <span class="me-2 copy-text">{{ !empty($maintype) ? route('category', [$maintype->slug,$type->slug]) : "" }}</span>
                        <a href="#" class="copy-button btn btn-sm btn-dark">
                            <small><i class="fa fa-copy"></i></small>
                        </a>
                        <small class="text-muted d-none copied-text">Copied</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-type'))
                        <a href="{{ route('admin.types.edit', $type->slug) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-type'))
                        <a href="#delete-modal-{{ $type->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $type->id }}" url="{{ route('admin.types.destroy', $type->id) }}"></x-admin.delete>
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
        {{ $types->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection