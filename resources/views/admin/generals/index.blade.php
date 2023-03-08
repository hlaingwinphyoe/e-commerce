@extends('layouts.admin')

@section('title', 'General Forms')

@section('classes', 'admin admin-general general-index')

@section('content')

<x-admin.search-box url="{{ route('admin.generals.index') }}"></x-admin.search-box>

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">General Usage</h2>
</div>

@include('components.admin.message')

<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
?>

<div class="border bg-white rounded p-2">
    <p class="me-2"><span class="fw-bold h5">{{ $generals->count() }}</span> of total <span class="">{{ $generals->total() }}</span></p>
    <div class="d-flex mb-3">
        <!-- filter -->
        <div class="d-flex flex-wrap">
            <div class="me-2 mt-4">
                <a href="{{ route('admin.generals.create') }}" class="btn btn-sm btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
        </div>

        <form action="{{ route('admin.generals.index') }}" class="d-flex responsive-flex align-items-end">
            <div class="form-group me-2">
                <label for="" class="form-label">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" placeholder="date">
            </div>
            <div class="form-group me-2">
                <label for="" class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="form-group me-2">
                <label for="" class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-secondary me-2 mb-1">Filter</button>
                <a href="{{ route('admin.generals.index') }}" class="btn btn-sm btn-danger mb-1">
                    <small><i class="fa fa-redo m-0"></i></small>
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>Form No.</th>
                    <th>Description</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>By</th>
                    <th><i class="fa fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>

                @forelse($generals as $general)
                <tr id="tr-{{ $general->id }}" class="align-middle">
                    <td>{{ $general->inventory_no }}</td>
                    <td class="">{{ $general->supplier ? $general->supplier->name : '' }}</td>
                    <td>
                        <a href="{{ route('admin.generals.show', $general->id) }}" class="badge bg-secondary p-2 text-decoration-none">
                            {{ $general->skus->count() }}
                        </a>
                    </td>
                    <td>
                        <small class="{{ $general->is_published == 1 ? 'text-success' : 'text-info' }}">{{ $general->is_published == 1 ? 'Confirmed' : 'Draft' }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($general->date)->format('M d, Y') }}</td>
                    <td>{{ $general->user ? $general->user->name : '' }}</td>
                    <td>
                        <div class="d-flex">
                            {{-- @if(auth()->user()->role->hasPermission('edit-general')) --}}
                            <a href="{{ route('admin.generals.edit', $general->id) }}" class="me-2 text-warning">
                                <span><i class="fa fa-pencil-alt"></i></span>
                            </a>
                            {{-- @endif --}}

                            <a href="{{ route('admin.generals.show', $general->id) }}" class="me-2 text-info"><i class="fa fa-eye "></i></a>

                            <a href="{{ route('admin.generals.print', $general->id) }}" class="me-2 text-success"><i class="fa fa-print"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">There is no result for you.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $generals->appends(request()->query->all())->links('components.pagination') }}
    </div>

</div>
@endsection
