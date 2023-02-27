@extends('layouts.admin')

@section('title', 'Transactions')

@section('classes', 'admin admin-transactions admin-transactions-index')

{{--@section('content-header')--}}
{{--<div class="d-flex content-header">--}}
{{--    <x-content-header :navs="['transactions']"></x-content-header>--}}
{{--    <form action="{{ route('admin.transaction.next-payment') }}" class="d-flex align-items-center px-2">--}}
{{--        <div class="input-group mb-2">--}}
{{--            <input type="text" name="order_no" class="form-control form-control-sm" placeholder="Search with order no." value="{{ request('order_no') }}">--}}
{{--            <div class="input-group-prepend">--}}
{{--                <button type="submit" class="btn btn-sm btn-primary">--}}
{{--                    <small><i class="fa fa-search"></i></small>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--</div>--}}

{{--@endsection--}}

@section('content')

    <x-admin.search-box url="{{ route('admin.transactions.index') }}"></x-admin.search-box>

<div>
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-primary btn-sm me-2">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Transaction</h4>
        <span class="text-muted form-text">( Showing {{ $trans->count() }} of total {{ $trans->total() }} records )</span>
    </div>

    <div class="d-flex flex-wrap">
        @if(auth()->user()->role->hasPermission('delete-transaction') && auth()->user()->role->slug == 'technician')
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
        <form action="{{ route('admin.transaction.next-payment') }}" class="d-flex responsive-flex">
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
                <button class="btn btn-sm btn-dark me-2">Filter</button>
                <a href="{{ route('admin.transaction.next-payment') }}" class="btn btn-sm btn-primary">
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
            <a href="{{ route('admin.transactions.index') }}{{ $query }}" class="nav-link pl-0">Transactions</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.transaction.next-payment') }}{{ $query }}" class="nav-link active">Next Payment Transactions</a>
        </li>
    </ul>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="">
                <tr>
                    <th>
                        <input type="checkbox" id="check-all">
                    </th>
                    <th>_ID</th>
                    <th>Customer/Supplier</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Next Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trans as $tran)
                <?php
                    $order = $tran->orders()->first();
                    $inven = $tran->inventories()->first();
                ?>
                @if($order && $order->getBalance())
                <tr id="tr-{{ $tran->id }}">
                    <td><input type="checkbox" id="check-{{ $tran->id }}" value="{{ $tran->id }}"></td>
                    <td>
                        <a href="{{ route('admin.pos.show', $order->id) }}">{{ $order->order_number }}</a>
                    </td>
                    <td>
                        <span>{{ $order->customer ? $order->customer->name : '' }}</span>
                        <p class="mb-0">{{ $order->customer ? $order->customer->phone : '' }}</p>
                    </td>
                    <td>{{ number_format($order->getBalance()) }}</td>
                    <td><span class="badge bg-success">ရရန်ကျန်</span></td>
                    <td>{{ \Carbon\Carbon::parse($tran->next_date)->format('d M, Y') }}</td>
                </tr>
                @elseif ($inven && $inven->getBalance())
                <tr id="tr-{{ $tran->id }}">
                    <td><input type="checkbox" id="check-{{ $tran->id }}" value="{{ $tran->id }}"></td>
                    <td>
                        <a href="{{ route('admin.inventories.edit', $inven->id) }}">PUR_{{ $inven->inventory_no }}</a>
                    </td>
                    <td>
                        <span>{{ $inven->supplier ? $inven->supplier->name : '' }}</span>
                        <p class="mb-0">{{ $inven->supplier ? $inven->supplier->phone : '' }}</p>
                    </td>
                    <td>{{ number_format($inven->getBalance()) }}</td>
                    <td><span class="badge bg-danger">ပေးရန်ကျန်</span></td>
                    <td>{{ \Carbon\Carbon::parse($tran->next_date)->format('d M, Y') }}</td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" class="text-center">There is no data still yet!</td>
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
