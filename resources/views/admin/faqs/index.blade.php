@extends('layouts.admin')

@section('title', 'Faqs')

@section('classes', 'admin admin-faqs admin-faqs-index')

@section('content')

<x-admin.search-box url="{{ route('admin.faqs.index') }}"></x-admin.search-box>


<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">FAQ</h4>
        <span class="text-muted form-text">( Showing {{ $faqs->count() }} of total {{ $faqs->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-faq'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.faqs.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif

        @if(auth()->user()->role->hasPermission('delete-faq'))
        <div class="me-2 mb-3">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="me-2 mb-3">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif
    </div>

    @include('components.admin.message')

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Desc</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr id="tr-{{ $faq->id }}">
                    <td><input type="checkbox" id="check-{{ $faq->id }}" value="{{ $faq->id }}"></td>
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
@if(auth()->user()->role->hasPermission('delete-faq'))
<x-admin.delete-all url="/wapi/faqs"></x-admin.delete-all>
@endif
@endsection
