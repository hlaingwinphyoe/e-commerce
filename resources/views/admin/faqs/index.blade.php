@extends('layouts.admin')

@section('title', 'Faqs')

@section('classes', 'admin admin-faqs admin-faqs-index')

@section('content')

<x-admin.search-box url="{{ route('admin.faqs.index') }}"></x-admin.search-box>

<div>
    <h3 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.faqs')}}</h3>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $faqs->count() }}</span> of total <span class="">{{ $faqs->total() }}</span></p>
    <div class="d-flex mb-3">
        <!-- filter -->
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-faq'))
            <div class="me-2 mb-1">
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.faqs.index') }}" class="d-flex responsive-flex">

                <div class="form-group me-2">
                    <select name="faq_type" class="form-select form-select-sm">
                        <option value="">Choose FAQ Type</option>
                        @foreach($faq_types as $type)
                        <option value="{{ $type->id }}" {{ $type->id == request()->faq_type? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-primary mb-1">
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
                    <th>Title</th>
                    <th>Type</th>
                    <th>Desc</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr id="tr-{{ $faq->id }}">
                    <td>{{ $faq->title }}</td>
                    <td>{{ $faq->faq_type->name }}</td>
                    <td>{{ substr($faq->desc, 0, 20) }} ...</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-faq'))
                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif

                        @if(auth()->user()->role->hasPermission('delete-faq'))
                        <a href="#delete-modal-{{ $faq->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $faq->id }}" url="{{ route('admin.faqs.destroy', $faq->id) }}"></x-admin.delete>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $faqs->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
