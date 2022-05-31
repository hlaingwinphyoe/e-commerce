@extends('layouts.admin')

@section('title', 'Region')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.regions.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.region')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $regions->count() }}</span> of total <span class="">{{ $regions->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            <div class="me-2 mb-3 ">
                <a href="{{ route('admin.regions.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>

            <form action="{{ route('admin.regions.index') }}" class="d-flex responsive-flex">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">

                <div class="form-group me-2">
                    <select name="country_id" class="form-select form-select-sm">
                        <option value="">Choose Country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ $country->id == request()->country_id? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.regions.index') }}" class="btn btn-sm btn-primary mb-1">
                        <small><i class="fa fa-redo m-0"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>

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
                    <td>{{ $region->name }}</td>
                    <td>{{ $region->mm_name ?? '-' }}</td>
                    <td>{{ $region->country->name }}</td>
                    <td>
                        @if($region->townships()->count())
                        <a href="{{ route('admin.townships.index') }}?region_id={{ $region->id }}" class="badge bg-primary">{{ $region->townships()->count() }}</a>
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
@endsection