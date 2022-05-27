@extends('layouts.admin')

@section('title', 'Township')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.townships.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">Townships</h4>
        <span class="text-muted form-text">( Showing {{ $townships->count() }} of total {{ $townships->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        <div class="me-2 mb-3">
            <a href="{{ route('admin.townships.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
                {{-- <option value="disabled">Disabled</option>
                <option value="enabled">Enabled</option> --}}
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="township">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
    </div>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.townships.index') }}?region_id={{ request('region_id') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.townships.index') }}?region_id={{ request('region_id') }}&disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>MM_Name</th>
                    <th>Regions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($townships as $township)
                <tr id="tr-{{ $township->id }}">
                    <td><input type="checkbox" id="check-{{ $township->id }}" value="{{ $township->id }}"></td>
                    <td>{{ $township->name }}</td>
                    <td>{{ $township->mm_name ?? '-' }}</td>
                   
                    <td>
                        {{ $township->region->name }}
                    </td>
                    <td>
                        <a href="{{ route('admin.townships.edit', $township->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        <a href="#delete-modal-{{ $township->id }}" class="btn btn-sm btn-danger mb-1" data-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $township->id }}" url="{{ route('admin.townships.destroy', $township->id) }}"></x-admin.delete>
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
        {{ $townships->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
<x-admin.delete-all url="/wapi/townships"></x-admin.delete-all>
@endsection