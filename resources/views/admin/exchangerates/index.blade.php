@extends('layouts.admin')

@section('title', 'Exchange Rate')


@section('content')

<x-admin.search-box url="{{ route('admin.exchangerates.index') }}"></x-admin.search-box>

<div class="d-flex align-items-center mb-2">
    <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('Exchange Rate')}}</h4>
    <span class="text-muted form-text">( Showing {{ $exchangerates->count() }} of total {{ $exchangerates->total() }} records )</span>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <div class="d-flex">
        <div class="d-flex flex-wrap mb-2">
            <div class="me-2 mt-2">
                <a href="{{ route('admin.exchangerates.create') }}" class="btn btn-secondary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>

            <form action="{{ route('admin.exchangerates.index') }}" class="d-flex responsive-flex align-items-end">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">

                <div class="form-group me-2">
                    <input type="text" class="form-control" name="currency" placeholder="Currency" value="{{ request('currency') }}">
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-secondary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.exchangerates.index') }}" class="btn btn-danger mb-1">
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
                    <th>Currency</th>
                    <th>Rate</th>
                    <th>Amt</th>
                    <th>Date</th>
                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($exchangerates as $exchangerate)
                <tr id="tr-{{ $exchangerate->id }}">
                    <td>{{ $exchangerate->currency->name }}</td>
                    <td>{{ number_format($exchangerate->rate, 5) }}</td>
                    <td>{{ number_format($exchangerate->mmk) }} MMK</td>
                    <td>
                        {{ $exchangerate->updated_at ? \Carbon\Carbon::parse($exchangerate->updated_at)->format('d M, Y') : $exchangerate->created_at->format('d M, Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.exchangerates.edit', $exchangerate->id) }}" class="me-2 text-warning">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $exchangerate->id }}">
                            <i class="fas fa-trash"></i>
                        </a>
                        <x-admin.delete id="{{ $exchangerate->id }}" url="{{ route('admin.exchangerates.destroy', $exchangerate->id) }}"></x-admin.delete>
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
        {{ $exchangerates->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection

