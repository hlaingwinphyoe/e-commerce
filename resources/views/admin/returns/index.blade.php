@extends('layouts.admin')

@section('title', 'Return')

@section('classes', 'admin admin-return return-index')

@section('content')

<x-admin.search-box url="{{ route('admin.returns.index') }}"></x-admin.search-box>

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.return')}}</h2>
</div>

@include('components.admin.message')


<?php
$query = '';
$query .= request('q') ? '?q=' . request('q') : '';
?>

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $returns->count() }}</span> of total <span class="">{{ $returns->total() }}</span></p>
    <div class="d-flex mb-3">
        <!-- filter -->
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-return'))
            <div class="me-2 mb-1">
                <a href="{{ route('admin.returns.create') }}" class="btn btn-sm btn-primary">
                    <small class="me-2"><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.returns.index') }}" class="d-flex responsive-flex">

                <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.returns.index') }}" class="btn btn-sm btn-primary mb-1">
                        <small><i class="fa fa-redo m-0"></i></small>
                    </a>
                </div>
            </form>
        </div>

    </div>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th width="250px">Return No.</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>By</th>
                    <th><i class="fa fa-ellipsis-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                <tr id="tr-{{ $return->id }}" class="align-middle">
                    <td class="">{{ $return->return_no }}</td>
                    <td>{{ $return->order && $return->order->customer ? $return->order->customer->name: '' }}</td>
                    <td>{{ $return->skus->count() }}</td>
                    <td>{{ number_format($return->price) }}</td>
                    <td>{{ $return->date ? Carbon\Carbon::parse($return->date)->format('M d, Y') : '' }}</td>
                    <td>{{ $return->user ? $return->user->name : '' }}</td>
                    <td>
                        <div class="d-flex">
                            @if(auth()->user()->role->hasPermission('edit-return'))
                            <a href="{{ route('admin.returns.edit', $return->id) }}" class="me-2 text-warning">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
                            @endif

                            <a href="{{ route('admin.returns.show', $return->id) }}" class="me-2 text-info">
                                <i class="fa fa-eye"></i>
                            </a>

                            @if(auth()->user()->role->hasPermission('delete-return') && !$return->skus->count())
                            <a href="#delete-modal-{{ $return->id }}" class="action-btn" data-bs-toggle="modal">
                                <span><i class="fas fa-trash"></i></span>
                            </a>
                            <x-admin.delete id="{{ $return->id }}" url="{{ route('admin.returns.destroy', $return->id) }}"></x-admin.delete>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">There is no result for you.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $returns->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>




@endsection
