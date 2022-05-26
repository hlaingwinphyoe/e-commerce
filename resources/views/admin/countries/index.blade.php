@extends('layouts.admin')

@section('title', 'Country')

@section('classes', 'admin admin-countries admin-countries-index')

@section('content')

<x-admin.search-box url="{{ route('admin.countries.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">Countries</h4>
        <span class="text-muted form-text">( Showing {{ $countries->count() }} of total {{ $countries->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        <div class="me-2 mb-3">
            <a href="{{ route('admin.countries.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
                {{-- <option value="disabled">Disabled</option> --}}
                {{-- <option value="enabled">Enabled</option> --}}
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary" data-route="country">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
    </div>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4">
        <li class="nav-item">
            <a href="{{ route('admin.countries.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.countries.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $country)
                <tr id="tr-{{ $country->id }}">
                    <td><input type="checkbox" id="check-{{ $country->id }}" value="{{ $country->id }}"></td>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->mm_name ?? '-' }}</td>
                   
                   
                    <td>
                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        <a href="#delete-modal-{{ $country->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $country->id }}" url="{{ route('admin.countries.destroy', $country->id) }}"></x-admin.delete>
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
        {{ $countries->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
<x-admin.delete-all url="/wapi/countries"></x-admin.delete-all>
@endsection