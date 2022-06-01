@extends('layouts.admin')

@section('title', 'Skus')

@section('classes', 'admin admin-skus admin-skus-show')

@section('content-header')

<div class="d-flex content-header">
    <x-admin.content-header :navs="['skus']"></x-admin.content-header>
</div>
@endsection

@section('content')

<div>
    <div class="d-flex">
        <div class="me-2">
            <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

        <div>
            <h1>{{ $sku->item ? $sku->item->name : '' }} {{ $sku->data ? '('.$sku->data.')' : '' }}</h1>
            <h6 class="text-success">Current Stock - {{ $sku->stock }}</h6>
        </div>
    </div>

    <!-- Date filter -->
    <form action="{{ route('admin.skus.show', $sku->id) }}" method="get" class="d-flex flex-wrap py-3">
        <div class="mb-2 me-2">
            <label class="form-label">From Date</label>
            <input type="date" class="form-control" name="from_date" placeholder="From Date" value="{{ $from_date->format('Y-m-d') }}">
        </div>
        <div class="mb-2 me-2">
            <label class="form-label">To Date</label>
            <input type="date" class="form-control" name="to_date" placeholder="From Date" value="{{ $to_date->format('Y-m-d') }}">
        </div>
        <div class="mb-2 align-self-end">
            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
        </div>
    </form>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="true">Inventory</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button" role="tab" aria-controls="return" aria-selected="false">Return</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sale-tab" data-bs-toggle="tab" data-bs-target="#sale" type="button" role="tab" aria-controls="sale" aria-selected="false">Sale</button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="wait-tab" data-bs-toggle="tab" data-bs-target="#wait" type="button" role="tab" aria-controls="wait" aria-selected="true">Wait</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="waste-tab" data-bs-toggle="tab" data-bs-target="#waste" type="button" role="tab" aria-controls="waste" aria-selected="false">Waste</button>
        </li>
       
    </ul>
    <div class="tab-content" id="myTabContent">
        <!-- Inventory -->
        <div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="home-tab">
            <div class="py-4">
                <h4>Inventories (Total - {{ $inventories->count() }})</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped border">
                        <thead class="bg-head">
                            <tr>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th class="text-end">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inventory)
                            <tr>
                                <td>{{ $inventory->pivot->qty }}</td>
                                <td>{{ $inventory->pivot->amount ? number_format($inventory->pivot->amount) : '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($inventory->updated_at)->format('d M, Y') }}</td>
                                <td class="text-end">{{ $inventory->user ? $inventory->user->name : '' }}</td>
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
        </div>

        <!-- Return -->
        <div class="tab-pane fade" id="return" role="tabpanel" aria-labelledby="return-tab">
            <div class="py-4">
                <h4>Return (Total - {{ $returns->count() }})</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped border">
                        <thead class="bg-head">
                            <tr>
                                <th>Qty</th>
                                <th>Order_id</th>
                                <th>Remark</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th class="text-end">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                            <tr>
                                <td>{{ $return->pivot->qty }}</td>
                                <td>{{ $return->order->order_no }}</td>
                                <td>{{ $return->pivot->remark }}</td>
                                <td>{{ $return->order->customer ? $return->order->customer->name : '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($return->updated_at)->format('d M, Y') }}</td>
                                <td class="text-end">{{ $return->user ? $return->user->name : '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="tab-pane fade" id="sale" role="tabpanel" aria-labelledby="sale-tab">
            <div class="py-4">
                <div class="table-responsive">
                    <h4>Total Sales (Total - {{ $orders->count() }})</h4>
                    <table class="table table-hover table-striped border">
                        <thead class="bg-head">
                            <tr>
                                <th>Order_Id</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th class="text-end">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_no }}</td>
                                <td>
                                    @if($order->customer)
                                        <p class="mb-0">{{ $order->customer->name }}</p>
                                        <div class="text-danger small">{{ $order->customer->phone }}</div>
                                        <span class="text-primary">{{ $order->buyer ? $order->buyer->role->name : '-' }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->getTotalQty() }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d M, Y') }}</td>
                                <td class="text-end">{{ $order->seller ? $order->seller->name : '' }}</td>
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

        <!-- Wait -->
        <div class="tab-pane fade" id="wait" role="tabpanel" aria-labelledby="wait-tab">
            <div class="py-4">
                <div class="table-responsive">
                    <h4>Total Waiting Count (Total - {{ $wait_orders->count() }})</h4>
                    <table class="table table-hover table-striped border">
                        <thead class="bg-head">
                            <tr>
                                <th>Order_Id</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th class="text-end">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wait_orders as $wait_order)
                            <tr>
                                <td>{{ $wait_order->order->order_no }}</td>
                                <td>{{ $wait_order->order->customer->name }}</td>
                                <td>{{ $wait_order->qty }}</td>
                                <td>{{ \Carbon\Carbon::parse($wait_order->order->updated_at)->format('d M, Y') }}</td>
                                <td class="text-end">{{ $wait_order->order->seller ? $wait_order->order->seller->name : '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">There is no sale still yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Waste -->
        <div class="tab-pane fade" id="waste" role="tabpanel" aria-labelledby="waste-tab">
            <div class="py-4">
                <div class="table-responsive">
                    <h4>Total Wastes (Total - {{ $wastes->count() }})</h4>
                    <table class="table table-hover table-striped border">
                        <thead class="bg-head">
                            <tr>
                                <th>Waste Type</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th>Remark</th>
                                <th class="text-end">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wastes as $waste)
                            <tr>
                                <td>{{ $waste->status->name }}</td>
                                <td>{{ $waste->amt }}</td>
                                <td>{{ \Carbon\Carbon::parse($waste->date)->format('d M, Y') }}</td>
                                <th>{{ $waste->remark }}</th>
                                <td class="text-end">{{ $waste->user ? $waste->user->name : '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">There is no sale still yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection