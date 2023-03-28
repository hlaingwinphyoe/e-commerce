@extends('layouts.admin')

@section('title', 'Bonupoints')

@section('classes', 'admin admin-bonuspoint admin-bonuspoint-index')

@section('content')
{{-- <x-admin.search-box url="{{ route('admin.bonuspoints.index') }}"></x-admin.search-box> --}}

<div>
    <h2 class="page-title {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.bonuspoints')}}</h2>
</div>

@include('components.admin.message')

<div class="border bg-white rounded px-2 py-4">
    <p class="me-2"><span class="fw-bold h5">{{ $bonuspoints->count() }}</span> of total <span class="">{{ $bonuspoints->total() }}</span></p>

    <div class="d-flex mb-3">
        <div class="d-flex flex-wrap mb-2">
            @if(auth()->user()->role->hasPermission('create-brand'))
            <div class="me-2 mb-3">
                <a href="{{ route('admin.bonuspoints.create') }}" class="btn btn-primary">
                    <small><i class="fa fa-plus"></i></small>
                    <span>Add New</span>
                </a>
            </div>
            @endif

            <form action="{{ route('admin.bonuspoints.index') }}" class="d-flex responsive-flex">
                <input type="hidden" name="disabled" value="{{ request('disabled') }}">

                <div class="form-group me-2">
                    <input type="text" name="points" value="{{ request('points') }}" placeholder="Search Points" class="form-control form-control-sm">
                </div>

                <div class="form-group me-2">
                    <select name="role" class="form-select form-select-sm">
                        <option value="">Choose Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == request()->role? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-primary me-2 mb-1">Filter</button>
                    <a href="{{ route('admin.bonuspoints.index') }}" class="btn btn-primary mb-1">
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
                    <th>Amount</th>
                    <th>Role</th>
                    <th>Points</th>
                    <th><i class="fa-solid fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bonuspoints as $bonuspoint)
                <tr id="tr-{{ $bonuspoint->id }}">
                    <td>{{ $bonuspoint->amt }}</td>
                    <td>{{ $bonuspoint->role->name }}</td>
                    <td>{{ $bonuspoint->points }}</td>
                    <td>
                        @if(auth()->user()->role->hasPermission('edit-brand'))
                        <a href="{{ route('admin.bonuspoints.edit', $bonuspoint->id) }}" class="btn btn-sm btn-primary me-2 mb-1">
                            <small><i class="fa fa-pencil-alt"></i></small>
                        </a>
                        @endif
                        @if(auth()->user()->role->hasPermission('delete-brand'))
                        <a href="#delete-modal-{{ $bonuspoint->id }}" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal">
                            <small><i class="fas fa-trash"></i></small>
                        </a>
                        <x-admin.delete id="{{ $bonuspoint->id }}" url="{{ route('admin.bonuspoints.destroy', $bonuspoint->id) }}"></x-admin.delete>
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
        {{ $bonuspoints->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
