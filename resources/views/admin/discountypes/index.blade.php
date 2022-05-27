@extends('layouts.admin')

@section('title', 'Discountypes')

@section('classes', 'admin admin-discountypes admin-discountypes-index')

@section('content')

<x-admin.search-box url="{{ route('admin.discountypes.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.discounts')}}</h4>
        <span class="text-muted form-text">( Showing {{ $discountypes->count() }} of total {{ $discountypes->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap mb-2">
        @if(auth()->user()->role->hasPermission('create-discount-type'))
        <div class="me-2 mb-3">
            <a href="{{ route('admin.discountypes.create') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-plus"></i></small>
                <span>Add New</span>
            </a>
        </div>
        @endif
        @if(auth()->user()->role->hasPermission('delete-discount-type'))
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
                    <th>Name</th>
                    <th>Discounts</th>
                    <th>Amt</th>
                    <th>Duration</th>
                    <th>Link</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($discountypes as $discountype)
                <tr id="tr-{{ $discountype->id }}">
                    <td><input type="checkbox" id="check-{{ $discountype->id }}" value="{{ $discountype->id }}"></td>
                    <td>{{ $discountype->name }}</td>
                    <td>
                        @if($discountype->discounts()->count())
                        <a href="{{ route('admin.items.index') }}?discountype={{ $discountype->slug }}" class="badge badge-primary">
                            <?php $count = App\Models\Item::isDiscountType($discountype->id)->count(); ?>
                            {{ $count }}
                        </a>
                        @else
                        <span>{{ $discountype->discounts()->count() }}</span>
                        @endif
                    </td>
                    <td>{{ $discountype->amt }} {{ $discountype->status->name }}</td>
                    <td>
                        {{ $discountype->start_date ? \Carbon\Carbon::parse($discountype->start_date)->format('d M, Y') : '' }} -
                        {{ $discountype->end_date ? \Carbon\Carbon::parse($discountype->end_date)->format('d M, Y') : '' }}
                    </td>
                    <td>
                        <?php /*
                        <span class="me-2 copy-text">{{ route('discounts', $discountype->slug) }}</span>
                        */ ?>
                        <a href="#" class="copy-button btn btn-sm btn-dark">
                            <small><i class="fa fa-copy"></i></small>
                        </a>
                        <small class="text-muted d-none copied-text">Copied</small>
                    </td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-discount-type'))
                        <a href="{{ route('admin.discountypes.edit', $discountype->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-discount-type'))
                        <a href="#delete-modal-{{ $discountype->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $discountype->id }}" url="{{ route('admin.discountypes.destroy', $discountype->id) }}"></x-admin.delete>
                        @endif
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
        {{ $discountypes->appends(request()->query->all())->links('components.pagination') }}
    </div>

</div>
@if(auth()->user()->role->hasPermission('delete-discount-type'))
<x-admin.delete-all url="/wapi/discountypes"></x-admin.delete-all>
@endif
@endsection
