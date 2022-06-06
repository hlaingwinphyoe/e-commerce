@extends('layouts.admin')

@section('title', 'Gifts Log')

@section('classes', 'admin admin-gift-logs admin-gift-logs-index')

@section('content')

<x-admin.search-box url="{{ route('admin.gift-logs.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex flex-wrap mb-4">
        <h4 class="page-title mb-0 me-2">Gifts Log</h4>
        <span class="text-muted form-text">( Showing {{ $user_gifts->count() }} of total {{ $user_gifts->total() }} records )</span>
    </div>

    <a href="{{ route('admin.gifts.index') }}" class="btn btn-sm btn-outline-primary mb-4">
        <i class="fa fa-arrow-left"></i>
        <span>Back to Gifts</span>
    </a>

    @include('components.admin.message')

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th width="200px"></th>
                    <th>Gift Name</th>
                    <th>User</th>
                    <th width="250px">Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user_gifts as $user_gift)
                <tr id="tr-{{ $user_gift->id }}">
                    <td>
                        <img src="{{ $user_gift->gift->thumbnail }}" alt="{{ $user_gift->gift->name }}" style="max-height: 50px;">
                    </td>
                    <td>{{ $user_gift->gift->name }}</td>
                    <td>{{ $user_gift->user->name }}</td>
                    <td>
                        <?php
                        $cancel_gift = App\Models\Status::where('slug', 'cancel-gift')->first();
                        ?>

                        @if($user_gift->status_id != $cancel_gift->id)
                        <form action="{{ route('admin.gift-logs.update', $user_gift->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group row">
                                <div class="col-auto">
                                    <select class="role-select form-select" name="status_id">
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $user_gift->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <span class="text-danger">Cancel Gift</span>
                        @endif
                    </td>
                    <td>{{ $user_gift->created_at->format('d M, Y') }}</td>
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
        {{ $user_gifts->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection