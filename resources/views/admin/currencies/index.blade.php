@extends('layouts.admin')

@section('title', 'Currency')


@section('content')

<x-admin.search-box url="{{ route('admin.currencies.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('Currency')}}</h4>
    <span class="text-muted form-text">( Showing {{ $currencies->count() }} of total {{ $currencies->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    
    <div class="d-flex ">
        <div class="d-flex flex-wrap mb-2">
            <div class="me-2 mt-2">
                <a href="{{ route('admin.currencies.create') }}" class="btn btn-secondary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>

            <form action="{{ route('admin.currencies.index') }}" class="d-flex responsive-flex align-items-end">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">

                <div class="form-group me-2">
                    <input type="text" class="form-control" name="currency" placeholder="Currency" value="{{ request('currency') }}">
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-secondary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-danger mb-1">
                        <small><i class="fa fa-redo m-0"></i></small>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($currencies as $currency)
                <tr id="tr-{{ $currency->id }}">
                    <td>{{ $currency->name }}</td>
                    <td>
                        {{ $currency->updated_at ? \Carbon\Carbon::parse($currency->updated_at)->format('d M, Y') : $currency->created_at->format('d M, Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.currencies.edit', $currency->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $currency->id }}">
                            <i class="fas fa-trash"></i>
                        </a>
                        <x-admin.delete id="{{ $currency->id }}" url="{{ route('admin.currencies.destroy', $currency->id) }}"></x-admin.delete>
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
        {{ $currencies->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection


