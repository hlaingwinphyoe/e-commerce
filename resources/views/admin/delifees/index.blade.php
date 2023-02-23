@extends('layouts.admin')

@section('title', 'Deli Fees')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.delifees.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.deli_fees')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $delifees->count() }}</span> of total <span class="">{{ $delifees->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-delifee'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.delifees.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.delifees.index') }}" class="d-flex responsive-flex">

                <div class="form-group me-2">
                    <select name="township_id" class="form-select form-select-sm">
                        <option value="">Choose Township</option>
                        @foreach($townships as $tsp)
                        <option value="{{ $tsp->id }}" {{ $tsp->id == request()->township_id? 'selected' : '' }}>{{ $tsp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.delifees.index') }}" class="btn btn-sm btn-primary mb-1">
                        <small><i class="fa fa-redo m-0"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Amt</th>
                    <th>Townships</th>
                    <th>User</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($delifees as $delifee)
                <tr id="tr-{{ $delifee->id }}" class="align-middle">
                    <td>{{ $delifee->amt }}</td>
                    <td>{{ $delifee->townships()->count() }}</td>
                    <td>
                        User
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-delifee'))
                        <a href="{{ route('admin.delifees.edit', $delifee->id) }}" class="me-2 text-warning">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-delifee'))
                        <a href="#delete-modal-{{ $delifee->id }}" class="" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $delifee->id }}" url="{{ route('admin.delifees.destroy', $delifee->id) }}"></x-admin.delete>
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
        {{ $delifees->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
