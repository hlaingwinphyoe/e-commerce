@extends('layouts.admin')

@section('title', 'Category')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.types.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.category')}}</h4>
    <span class="text-muted form-text">( Showing {{ $types->count() }} of total {{ $types->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="small text-secondary">Priority စီလျှင် ငယ်ရာမှကြီးရာသို့ စီထားပါသည်။ (Eg. 0,1,2,3,4,... )</p>

    <div class="d-flex">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-type'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.types.create') }}" class="btn btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.types.index') }}" class="d-flex responsive-flex">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">
                <div class="form-group me-2">
                    <select name="parent_type" class="form-select form-select-sm">
                        <option value="">Choose Main Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == request()->parent_type? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-secondary me-2">Filter</button>
                    <a href="{{ route('admin.types.index') }}" class="btn btn-danger">
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
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr id="tr-{{ $type->id }}" class="align-middle">
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->parent_type ? $type->parent_type->name : ' - ' }}</td>
                    <td>
                        @if($type->items()->count())
                        <a href="{{ route('admin.items.index') }}?type={{ $type->slug }}" class="badge bg-primary text-decoration-none">
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
                        <a href="#" class="copy-button text-success">
                            <i class="fa fa-copy"></i>
                        </a>
                        <small class="text-muted d-none copied-text">Copied</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-type'))
                        <a href="{{ route('admin.types.edit', $type->slug) }}" class="me-2 text-warning">
                            <i class="fa-solid fa-pencil-alt"></i>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-type'))
                        <a href="#delete-modal-{{ $type->id }}" data-bs-toggle="modal" class="text-danger">
                            <i class="fa-solid fa-trash"></i>
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
