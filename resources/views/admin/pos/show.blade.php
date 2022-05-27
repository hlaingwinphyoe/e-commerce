@extends('layouts.admin')

@section('title', 'Sale Lists')

@section('classes', 'admin admin-pos admin-pos-index')

@section('content')

<?php
$address = App\Models\Group::isType('address')->first();
$phone = App\Models\Group::where('type', 'like', 'phone-%')->first();
?>

<div>
    <div class="d-flex align-items-center">
        <a href="{{ request()->session()->get('prev_route') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h5 class="ms-2 mb-0 fw-bold">{{ $order->order_number }}</h6>
    </div>

    @if($order->status->slug == 'cancel')
    <h5 class="alert alert-danger mt-2">This order is already cancelled / returned.</h5>
    @endif

    <div class="row py-4">
        <div class="col-md-6">
            <div class="slip-container w-100 bg-sidebar py-4 px-4">
                <div class="pb-2 text-center">
                    <h6 class="text-uppercase fw-bold mb-0">{{ config('app.name') }}</h6>
                    <p class="mb-0">
                        {{ $address ? $address->name : '' }} Ph-{{ $phone ? $phone->name : '' }}
                    </p>
                </div>

                <table class="w-100">
                    <tr>
                        <td class="w-50"><small>Name - {{ $order->customer ? $order->customer->name : '' }} {{ $order->customer && $order->customer->phone ? '( '. $order->customer->phone .' )' : '' }}</small></td>
                        <td class="w-50"><small>Date - {{ Carbon\Carbon::parse($order->updated_at)->format('d M, Y H:i A') }}</small></td>
                    </tr>
                    <tr>
                        <td><small>Address - {{ $order->customer ? $order->customer->address : '' }}</small></td>
                        <td><small>Vr No. - {{ $order->order_no }}</small></td>
                    </tr>
                </table>

                <div class="py-2">
                    <table class="w-100 table table-sm small">
                        <thead class="">
                            <tr class="small">
                                <th width="600px">Name</th>
                                <th>Qty</th>
                                <th width="200px" style="text-align:right">Price</th>
                                <th width="200px" style="text-align:right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->skus as $index => $sku)
                            <tr>
                                <td>{{ $sku->item->name }} {{ $sku->data ? '('.$sku->data.')' : '' }}</td>
                                <td>{{ $sku->pivot->qty }}</td>
                                <td style="text-align:right">{{ number_format($sku->pivot->price) }}</td>
                                <td class="" style="text-align:right">{{ number_format($sku->pivot->qty * $sku->pivot->price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <thead class="">
                            <tr class="small" style="text-align:right">
                                <th colspan="3" class="" style="text-align:right">Total</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->price) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th colspan="3" class="" style="text-align:right">Discount</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->discount) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th colspan="3" class="" style="text-align:right">Deli Fee</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->deli_fee) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th colspan="3" class="" style="text-align:right">Net Amount</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->price - $order->discount + $order->deli_fee) }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

        @if($order->status->slug != 'cancel')
        <div class="col-md-6">
            @if($order->getBalance())
            <h3 class="text-secondary">This order is not paid.</h3>
            @else
            <?php
            $tr = $order->transactions()->whereHas('status', function ($q) {
                $q->where('slug', 'in');
            })->first();
            ?>
            @if($tr)
            <h3 class="text-success">This order is paid with <span class="fw-bold">{{ $tr->paymentype->name }}</span></h3>
            @endif
            @endif

            <form action="{{ route('admin.orders.return', $order->id) }}" method="post">
                @csrf

                @if(!$order->getBalance())
                <div class="form-group">
                    <label for="">Choose Payment for Return</label>
                    <select name="paymentype_id" class="form-select">
                        @foreach($paymentypes as $paymentype)
                        <option value="{{ $paymentype->id }}">{{ $paymentype->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="form-group">
                    <label for="">Remark</label>
                    <textarea name="remark" class="form-control form-control-sm" rows="3" placeholder="Remark for return"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-secondary">Make Return</button>
                </div>

            </form>
        </div>
        @endif
    </div>
</div>

@endsection