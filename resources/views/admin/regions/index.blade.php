@extends('layouts.admin')

@section('title', 'Region')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.regions.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">Regions</h4>
        <span class="text-muted form-text">( Showing {{ $regions->count() }} of total {{ $regions->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        <div class="me-2 mb-3 ">
            <a href="{{ route('admin.regions.create') }}" class="btn btn-sm btn-primary">
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
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="region">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
    </div>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.regions.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.regions.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Country</th>
                    <th>Townships</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regions as $region)
                <tr id="tr-{{ $region->id }}">
                    <td><input type="checkbox" id="check-{{ $region->id }}" value="{{ $region->id }}"></td>
                    <td>{{ $region->name }}</td>
                    <td>{{ $region->mm_name ?? '-' }}</td>
                    <td>{{ $region->country->name }}</td>
                    <td>
                        @if($region->townships()->count())
                        <a href="{{ route('admin.townships.index') }}?region_id={{ $region->id }}" class="badge badge-primary">{{ $region->townships()->count() }}</a>
                        @else
                        <span>0</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.regions.edit', $region->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        <a href="#delete-modal-{{ $region->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $region->id }}" url="{{ route('admin.regions.destroy', $region->id) }}"></x-admin.delete>
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
        {{ $regions->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
<x-admin.delete-all url="/wapi/regions"></x-admin.delete-all>
@endsection