@extends('layouts.admin')

@section('title', 'Category')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.types.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-2">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.category')}}</h4>
        <span class="text-muted form-text">( Showing {{ $types->count() }} of total {{ $types->total() }} records )</span>
    </div>
    <p class="small bg-light text-secondary px-3 py-2">Priority စီလျှင် ငယ်ရာမှကြီးရာသို့ စီထားပါသည်။ (Eg. 0,1,2,3,4,... )</p>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-type'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.types.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-type'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
                <option value="disabled">Disabled</option>
                <option value="enabled">Enabled</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="type">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif
        <form action="{{ route('admin.types.index') }}" class="d-flex responsive-flex d-none">
            <input type="hidden" name="disabled" value="{{ request('disabled') }}">
            <div class="form-group me-2">
                <select name="maintype" class="form-select">
                    <option value="">Select Main Category</option>
                    @forelse($maintypes as $maintype)
                    <option value="{{ $maintype->id }}" {{ request('maintype') == $maintype->id ? 'selected' : '' }}>{{ $maintype->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.types.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

    @include('components.admin.message')

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
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
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
                    <td><input type="checkbox" id="check-{{ $type->id }}" value="{{ $type->id }}"></td>
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
@if(auth()->user()->role->hasPermission('delete-type'))
<x-admin.delete-all url="/wapi/types"></x-admin.delete-all>
@endif
@endsection