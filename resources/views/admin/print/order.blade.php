@extends('layouts.print')

@section('title', 'Invoice:' . $sale->order_no)

@section('style')
<style>

</style>
@endsection

@section('content')

<div class="slip-container w-100">
    <div class="pb-2 text-center">
        <h6 class="text-uppercase fw-bold mb-0">{{ config('app.name') }}</h6>
        <p class="mb-0">
            Address
        </p>
        <p class="mb-0">Ph-Phone</p>
    </div>

    <table class="w-100">
        <tr>
            <td class="w-50"><small>Name - {{ $sale->customer ? $sale->customer->name : '' }} {{ $sale->customer && $sale->customer->phone ? '( '. $sale->customer->phone .' )' : '' }}</small></td>
            <td class="w-50 text-end"><small>Date - {{ now()->format('d M, Y H:i A') }}</small></td>
        </tr>
        <tr>
            <td><small>Address - {{ $sale->customer ? $sale->customer->address : '' }}</small></td>
            <td class="text-end"><small>Vr No. - {{ $sale->order_no }}</small></td>
        </tr>
    </table>

    <div class="py-2">
        <table class="w-100 table table-sm small" style="border-bottom: 2px solid rgba(12, 42, 160, 0.7)">
            <thead class="">
                <tr class="small">
                    <th width="600px">Name</th>
                    <th>Qty</th>
                    <th width="200px" style="text-align:right">Price</th>
                    <th width="200px" style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->skus as $index => $sku)
                <tr>
                    <td>{{ $sku->item->name }} {{ $sku->data ? '('.$sku->data.')' : '' }}</td>
                    <td>{{ $sku->pivot->qty }}</td>
                    <td style="text-align:right">{{ number_format($sku->pivot->price) }}</td>
                    <td class="" style="text-align:right">{{ number_format($sku->pivot->qty * $sku->pivot->price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <tr class="small" style="text-align:right">
                <th width="600px"></th>
                <th></th>
                <th width="200px" style="text-align:right">Total</th>
                <th width="200px" style="text-align:right">{{ number_format($sale->price) }}</th>
            </tr>
            <tr class="small" style="text-align:right">
                <th width="600px"></th>
                <th></th>
                <th width="200px" style="text-align:right">Discount</th>
                <th width="200px" style="text-align:right">{{ number_format($sale->discount) }}</th>
            </tr>
            <tr class="small" style="text-align:right">
                <th width="600px"></th>
                <th></th>
                <th width="200px" style="text-align:right">Deli Fee</th>
                <th width="200px" style="text-align:right">{{ number_format($sale->deli_fee) }}</th>
            </tr>
            <tr class="small" style="text-align:right">
                <th width="600px"></th>
                <th></th>
                <th width="200px" style="text-align:right">Net Amount</th>
                <th width="200px" style="text-align:right">{{ number_format($sale->price - $sale->discount + $sale->deli_fee) }}</th>
            </tr>
            @if($sale->getPayAmount())
            <tr class="small" style="text-align:right">
                <th width="600px"></th>
                <th></th>
                <th width="200px" style="text-align:right">Pay Amount</th>
                <th width="200px" style="text-align:right"><span>{{ $sale->getBalance() <= 0 ? '(Paid) ' : '' }}</span>{{ number_format($sale->getPayAmount()) }}</th>
            </tr>
            @endif
        </table>
    </div>
    <p class="text-center small">** Thanks for shopping with us. **</p>

</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        (function() {
            window.print();
            window.onafterprint = function(e) {
                window.history.go(-1);
            };
        }());
    });
</script>
@endsection