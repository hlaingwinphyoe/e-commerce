@extends('layouts.admin')

@section('title', 'Expenses')

@section('classes', 'admin admin-expenses admin-expenses-index')

@section('content')

<x-admin.search-box url="{{ route('admin.expenses.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">{{__('Expenses')}}</h4>
        <span class="text-muted form-text">( Showing {{ $expenses->count() }} of total {{ $expenses->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-1">
        @if(auth()->user()->role->hasPermission('create-expense'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif


        <form action="{{ route('admin.expenses.index') }}" class="d-flex responsive-flex">

            <div class="form-group me-2">
                <select name="type" class="form-select">
                    <option value="">Select Type</option>
                    @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Search Date" class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

    @include('components.admin.message')

    <?php
    $query = '';
    $query .= request('type') ? '?type=' . request('type') : '';
    if (request('date')) {
        $query .= $query ? '&' : '?';
        $query .= 'date=' . request('date');
    }
    ?>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>By</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr id="tr-{{ $expense->id }}">
                    <td>{{ $expense->name }}</td>
                    <td>{{ number_format($expense->amount) }}</td>
                    <td>{{ $expense->type ? $expense->type->name : '' }}</td>
                    <td>{{ $expense->supplier ? $expense->supplier->name : '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                    <td>{{ $expense->user ? $expense->user->name : '' }}</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-expense') && $expense->canEdit())

                        <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-expense') && $expense->canEdit())
                        <a href="#" class="text-danger" data-bs-target="#delete-modal-{{ $expense->id }}" data-bs-toggle="modal">
                            <i class="fas fa-trash"></i>
                        </a>
                        <x-admin.delete id="{{ $expense->id }}" url="{{ route('admin.expenses.destroy', $expense->id) }}"></x-admin.delete>
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
        {{ $expenses->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>

@endsection
