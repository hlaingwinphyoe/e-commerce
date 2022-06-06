@extends('layouts.admin')

@section('title', 'Gifts')

@section('classes', 'admin admin-gifts admin-gifts-show')

@section('content-header')

<div class="d-flex content-header">
    <x-admin.content-header :navs="['gifts']"></x-admin.content-header>
</div>
@endsection

@section('content')

<div>
    <div class="d-flex">
        <div class="mr-2">
            <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <div>
            <h1>{{ $gift->name }}</h1>
            <h6 class="text-success">Current Stock - {{ $gift->stock }}</h6>
        </div>
    </div>


    <div class="row py-3">
        <div class="col-md-6 mb-3">
            <h4>Inventories (Total - {{ $gift->getTotalInventoriesCount() }})</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped border">
                    <thead class="bg-head">
                        <tr>
                            <th>Qty</th>
                            <th>Date</th>
                            <th class="text-right">By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gift->inventories()->where('is_published', 1)->get() as $inventory)
                        <tr>
                            <td>{{ $inventory->qty }}</td>
                            <td>{{ \Carbon\Carbon::parse($inventory->date)->format('d M, Y') }}</td>
                            <td class="text-right">{{ $inventory->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">There is no inventory still yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div class="col-md-6 mb-3">
            <div class="table-responsive">
                <h4>Total Sales (Total - {{ $gift->getTotalUsedCount() }})</h4>
                <table class="table table-hover table-striped border">
                    <thead class="bg-head">
                        <tr>
                            <th>User</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gift->userGifts as $user_gift)
                        <tr>
                            <td>{{ $user_gift->user->name }}</td>
                            <td>{{ 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($user_gift->date)->format('d M, Y') }}</td>
                            <td>{{ $user_gift->status->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">There is no sale still yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection