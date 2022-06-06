@extends('layouts.admin')

@section('title', 'Gifts')

@section('classes', 'admin admin-gifts admin-gifts-index')

@section('content-header')
<div class="d-flex content-header">
    <x-admin.content-header :navs="['user-gifts']"></x-admin.content-header>
</div>
@endsection

@section('content')

<div>
    <div class="d-flex flex-wrap mb-4">
        <div class="me-2">
            <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary">
                <small><i class="fa fa-arrow-left"></i></small>
            </a>
        </div>
        <h4 class="page-title mb-0 me-2">Gifts</h4>
        <span class="text-muted form-text">( Showing {{ $gifts->count() }} of total {{ $gifts->total() }} records )</span>
    </div>

    @include('components.admin.message')

    <ul class="nav site-nav-tabs mb-4 d-none">
        <li class="nav-item">
            <a href="{{ route('admin.gifts.index') }}" class="nav-link {{ request('disabled') != 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell"></i>
                Active
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.gifts.index') }}?disabled=disabled" class="nav-link {{ request('disabled') == 'disabled' ? 'active' : ''  }}">
                <i class="far fa-bell-slash"></i>
                Disabled
            </a>
        </li>
    </ul>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="">
                <tr>
					<th width="200px"></th>
                    <th>Name</th>
                    <th>Point</th>
                    <th>Status</th>
                    <th><i class="fas fa-border-style"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($gifts as $user_gift)
                <tr id="tr-{{ $user_gift->id }}">
					<td>
						<img src="{{ $user_gift->gift->thumbnail }}" alt="{{ $user_gift->gift->name }}" style="max-height: 50px;">
					</td>
                    <td>{{ $user_gift->gift->name }}</td>
					<td>{{ $user_gift->gift->points }}</td>
					<td>{{ $user_gift->status->name }}</td>
                    <td>
                       @if($user_gift->status->slug == 'pending')
                       <form action="{{ route('admin.user-gifts.delete', $user_gift->id) }}" method="post">
                           @csrf
                           @method('delete')
                           <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                       </form>
                       @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate">
        {{ $gifts->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>
@endsection
