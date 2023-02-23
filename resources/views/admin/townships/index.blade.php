@extends('layouts.admin')

@section('title', 'Township')

@section('classes', 'admin admin-types admin-types-index')

@section('content')

<x-admin.search-box url="{{ route('admin.townships.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.township')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $townships->count() }}</span> of total <span class="">{{ $townships->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            <div class="me-2 mb-3">
                <a href="{{ route('admin.townships.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>

            <form action="{{ route('admin.townships.index') }}" class="d-flex responsive-flex">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">

                <div class="form-group me-2">
                    <select name="region_id" class="form-select form-select-sm">
                        <option value="">Choose Region</option>
                        @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ $region->id == request()->region_id? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.townships.index') }}" class="btn btn-sm btn-primary mb-1">
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
                    <th>Name</th>
                    <th>MM_Name</th>
                    <th>Regions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($townships as $township)
                <tr id="tr-{{ $township->id }}" class="align-middle">
                    <td>{{ $township->name }}</td>
                    <td>{{ $township->mm_name ?? '-' }}</td>

                    <td>
                        {{ $township->region->name }}
                    </td>
                    <td>
                        <a href="{{ route('admin.townships.edit', $township->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a href="#delete-modal-{{ $township->id }}" class="" data-toggle="modal">
                            <i class="fas fa-trash"></i>
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
@endsection
