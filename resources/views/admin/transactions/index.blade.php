@extends('layouts.admin')

@section('title', 'Transactions')

@section('classes', 'admin admin-transactions admin-transactions-index')

@section('content')

<x-admin.search-box url="{{ route('admin.transactions.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2 {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.transactions')}}</h4>
        <span class="text-muted form-text">( Showing {{ $trans->count() }} of total {{ $trans->total() }} records )</span>
    </div>

    <div class="d-flex">
        @if(auth()->user()->role->hasPermission('delete-transaction') && auth()->user()->role->slug == 'technician')
        <div class="me-2">
            <select id="actions" name="action" class="form-select">
                <option value="">Select action</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="me-2">
            <button id="apply-actions" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-check me-2"></i>
                <span>Apply</span>
            </button>
        </div>
        @endif
        <form action="{{ route('admin.transactions.index') }}" class="d-flex responsive-flex">
            <div class="form-group me-2">
                <select name="paymentype_id" class="form-select">
                    <option value="">Select Payment Type</option>
                    @foreach($payments as $payment)
                    <option value="{{ $payment->id }}" {{ request()->paymentype_id == $payment->id ? 'selected' : '' }}>{{ $payment->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group me-2">
                <select name="type" class="form-select">
                    <option value="">Select Order Type</option>
                    <option value="order" {{ request()->type == 'order' ? 'selected' : '' }}>Order</option>
                    <option value="pos" {{ request()->type == 'pos' ? 'selected' : '' }}>POS</option>
                    <option value="pre-order" {{ request()->type == 'pre-order' ? 'selected' : '' }}>Pre Order</option>
                </select>
            </div>
            <div class="form-group me-2">
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="form-group me-2">
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-outline-primary me-2">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
    </div>

    @include('components.admin.message')

    <?php
    $query = '?';
    $query .= request('paymentype_id') ? 'paymentype_id=' . request('paymentype_id') : '';
    if (request('from_date')) {
        $query .= request('paymentype_id') ? '&from_date=' . request('from_date') : '?from_date=' . request('from_date');
    }
    if (request('to_date')) {
        $query .= request('paymentype_id') || request('from_date') ? '&to_date=' . request('to_date') : '?to_date=' . request('to_date');
    }
    if (request('type')) {
        $query .= request('paymentype_id') || request('from_date') || request('to_date') ? '&type=' . request('type') : '?type=' . request('type');
    }
    ?>

    <ul class="nav site-nav-tabs mb-3">
        <li class="nav-item">
            <a href="{{ route('admin.transactions.index') }}{{ $query }}" class="nav-link ps-0 active">Transactions</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.transaction.next-payment') }}{{ $query }}" class="nav-link">Next Payment Transactions</a>
        </li>
    </ul>

    <div class="table-responsive">
        <table class="table table-borderless">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>Payment Type</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>_ID</th>
                    <th>Remark</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trans as $tran)
                <tr id="tr-{{ $tran->id }}">
                    <td><input type="checkbox" id="check-{{ $tran->id }}" value="{{ $tran->id }}"></td>
                    <td>{{ $tran->paymentype->name }}</td>
                    <td>
                        <span>{{ Carbon\Carbon::parse($tran->date)->format('d M, Y') }}</span>
                        <p class="mb-0 text-muted">{{ Carbon\Carbon::parse($tran->created_at)->format('H:i A') }}</p>
                    </td>
                    <td>{{ number_format($tran->amount) }}</td>
                    <td>
                        <span class="badge {{ $tran->status->slug == 'in' ? 'bg-success' : 'bg-danger' }}">{{ $tran->status->name }}</span>
                    </td>
                    <td>
                        @if($order = $tran->orders()->first())
                        <a href="{{ route('admin.pos.show', $order->id) }}">
                            <span>{{ $order->order_number }}</span>
                            <p class="mb-0">{{ $order->customer ? $order->customer->name : '' }}</p>
                        </a>
                        @elseif($inven = $tran->inventories()->first())
                        <a href="{{ route('admin.inventories.edit', $inven->id) }}">
                            <span>PUR_{{ $inven->inventory_no }}</span>
                            <p class="mb-0">{{ $tran->supplier ? $tran->supplier->name : '' }}</p>
                        </a>
                        @endif
                    </td>
                    <td><span class="text-secondary">{{ $tran->remark }}</span></td>
                    <td>{{ $tran->user ? $tran->user->name : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">There is no data still yet!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="paginate">
        {{ $trans->appends(request()->query->all())->links('components.pagination') }}
    </div>
</div>

@endsection
