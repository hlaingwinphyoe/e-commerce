@extends('layouts.print')

@section('title', 'Purchase:' . $inventory->inventory_no)

@section('style')
<style>

</style>
@endsection

@section('content')

<div class="slip-container w-100">
    <div class="pb-2 text-center">
        <h5>{{ config('app.name') }}</h5>
        <h6 class="text-uppercase fw-bold mb-0">Purchase Form</h6>
    </div>

    <table class="w-100">
        <tr>
            <td class="w-50"><small>No. - {{ $inventory->inventory_no }}</small></td>
            <td class="w-50" style="text-align:right"><small>Date - {{ Carbon\Carbon::parse($inventory->date)->format('d M, Y H:i A') }}</small></td>
        </tr>
        <tr>
            <td><small>Supplier - {{ $inventory->supplier ? $inventory->supplier->name: '' }}</small></td>
            <td style="text-align:right"><small>Warehosue - {{ $inventory->warehouse ? $inventory->warehouse->name : '' }}</small></td>
        </tr>
    </table>

    <div class="py-2">
        <table class="w-100 table table-sm small">
            <thead class="">
                <tr class="small">
                    <th>No.</th>
                    <th width="600px">Name</th>
                    <th width="200px" style="text-align:right">Qty</th>
                    <th width="200px" style="text-align:right">Price</th>
                    <th width="200px" style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory->skus as $index => $sku)               
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $sku->item_name }} {{ $sku->data ? '('. $sku->data .')'  : ''}} {{$sku->per_unit && $sku->unit ? '-' . $sku->per_unit . '/ ' . $sku->unit->name : ''}}</td>
                    <td style="text-align:right">{{ $sku->pivot->qty }}</td>
                    <td style="text-align:right">{{ number_format($sku->pivot->price) }}</td>
                    <td class="" style="text-align:right">{{ number_format($sku->pivot->qty * $sku->pivot->price) }}</td>
                </tr>
                @endforeach
            </tbody>
            <thead>
                <tr>
                    <th colspan="4" style="text-align:right">Total</th>
                    <th style="text-align: right">{{ $inventory->getAmount() }}</th>
                </tr>
            </thead>
        </table>
    </div>
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