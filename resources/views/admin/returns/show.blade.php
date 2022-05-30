@extends('layouts.admin')

@section('title', 'Return')

@section('classes', 'admin admin-returns admin-returns-edit')

@section('content')

@include('components.admin.message')

<div>
    <div class="d-flex mb-4">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary me-2">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="page-title mb-0 me-2">Return</h4>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="bg-white shadow rounded px-2 py-3">
                <table class="w-100 mb-3">
                    <tr>
                        <td>Order - <span class="fw-bold">{{ $return->order ? $return->order_id : '' }}</span></td>
                        <td class="text-end">Date - <span class="fw-bold">{{ Carbon\Carbon::parse($return->date)->format('M d, Y') }}</span> </td>
                    </tr>
                </table>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return->skus as $index => $sku)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $sku->item_name }} {{ $sku->data ? "(" . $sku->data . ")" : '' }}</td>
                                <td>{{ $sku->pivot->qty }}</td>
                                <td class="text-end">{{ number_format($sku->pivot->price) }}</td>
                                <td class="text-end">{{ number_format($sku->pivot->price  * $sku->pivot->qty) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <thead>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end">{{ number_format($return->price) }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection