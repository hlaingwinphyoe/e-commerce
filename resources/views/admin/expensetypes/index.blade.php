@extends('layouts.admin')

@section('title', 'Expense Types')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.expensetypes.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-2">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">Expense Types</h4>
        <span class="text-muted form-text">( Showing {{ $expensetypes->count() }} of total {{ $expensetypes->total() }} records )</span>
    </div>
    <p class="small bg-light text-secondary px-3 py-2">Priority စီလျှင် ငယ်ရာမှကြီးရာသို့ စီထားပါသည်။ (Eg. 0,1,2,3,4,... )</p>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-expense'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.expensetypes.create') }}" class="btn btn-sm btn-secondary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-expense'))
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
        <form action="{{ route('admin.expensetypes.index') }}" class="d-flex responsive-flex d-none">
            <input type="hidden" name="disabled" value="{{ request('disabled') }}">
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.expensetypes.index') }}" class="btn btn-sm btn-danger">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

    <?php $query = request('expensetype') ? '?type=cate' : ''; ?>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.expensetypes.index') }}{{ $query }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.expensetypes.index') }}{{ $query ? $query . '&' : '?' }}disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Priority</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($expensetypes as $expensetype)
                <tr id="tr-{{ $expensetype->id }}">
                    <td><input type="checkbox" id="check-{{ $expensetype->id }}" value="{{ $expensetype->id }}"></td>
                    <td>{{ $expensetype->name }}</td>
                    <td>
                        <form action="{{ route('admin.types.change-priority', $expensetype->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div>
                                <input type="text" name="priority" placeholder="Priority" class="form-control form-control-sm role-select" value="{{ $expensetype->priority }}" style="max-width: 85px">
                            </div>
                        </form>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-type'))
                        <a href="{{ route('admin.expensetypes.edit', $expensetype->id) }}" class="text-warning me-2">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-type'))
                        <a href="#" class="text-danger" data-bs-target='#delete-modal-{{ $expensetype->id }}' data-bs-toggle="modal">
                            <i class="fas fa-trash"></i>
                        </a>
                        <x-admin.delete id="{{ $expensetype->id }}" url="{{ route('admin.expensetypes.destroy', $expensetype->id) }}"></x-admin.delete>
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
        {{ $expensetypes->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>

@endsection
