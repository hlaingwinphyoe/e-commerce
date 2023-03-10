@extends('layouts.admin')

@section('title', 'Country')

@section('classes', 'admin admin-countries admin-countries-index')

@section('content')

<x-admin.search-box url="{{ route('admin.countries.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.country')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $countries->count() }}</span> of total <span class="">{{ $countries->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            <div class="me-2 mb-3">
                <a href="{{ route('admin.countries.create') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>Name</th>
                    <th>MM_Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $country)
                <tr id="tr-{{ $country->id }}" class="align-middle">
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->mm_name ?? '-' }}</td>


                    <td>
                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a href="#delete-modal-{{ $country->id }}" class="" data-bs-toggle="modal">
                            <i class="fas fa-trash"></i>
                        </a>
                        <x-admin.delete id="{{ $country->id }}" url="{{ route('admin.countries.destroy', $country->id) }}"></x-admin.delete>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $countries->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
