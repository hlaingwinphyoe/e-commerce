@extends('layouts.admin')


@section('content')


{{--<header id="site-header" class="sticky-top">--}}
{{--    @include('components.site.home-header')--}}
{{--</header>--}}

{{--<x-app-sidebar></x-app-sidebar>--}}

{{--<div class="app-overlay sidebar-toggle"></div>--}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 py-4">

            <h3 class="alert alert-warning d-none">Invoice အား Download လုပ်ဆောင်နေပါသည်။ ကျေးဇူးပြု၍ ခေတ္တ စောင့်ဆိုင်းပေးပါ။</h3>

            <div id="image-container"></div>

            <div id="download-btn-container" class="py-2"></div>

            <div id="save-invoice" class="save-invoice bg-white" data-id="{{ $order->order_no }}">
                <div class="invoice-container px-2 py-3">
                    <div class="pb-2 text-center d-none">
                        <div class="mb-1">
                            <img src="{{ Storage::url('images/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 55px">
                        </div>
                        <h6 class="text-uppercase fw-bold">{{ config('app.name') }}</h6>
                        <p class="fw-bold small mb-0">{{ env('APP_DOMAIN') }}</p>
                        <p class="mb-0 small">
                            {{ $address ? $address->name : '' }}
                        </p>
                        <p class="mb-0 small">Ph-{{ $phone ? $phone->name : '' }}</p>
                    </div>

                    <table class="w-100">
                        <tr>
                            <td class="w-50 text-muted small">Name -<span class="text-dark"> {{ $order->customer ? $order->customer->name : '' }} {{ $order->customer && $order->customer->phone ? '( '. $order->customer->phone .' )' : '' }}</span></td>
                            <td class="w-50 text-muted small">Date -<span class="text-dark"> {{ now()->format('d M, Y H:i A') }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Address - <span class="text-dark">{{ $order->customer ? $order->customer->address : '' }}</span></td>
                            <td class="text-muted small">Vr No. - <span class="text-dark">{{ $order->order_no }}</span></td>
                        </tr>
                    </table>

                    <div class="py-2">
                        <table class="w-100 table table-sm small" style="border-bottom: 3px solid #640005">
                            <thead class="thead-bg">
                                <tr class="small">
                                    <th width="600px">Name</th>
                                    <th>Qty</th>
                                    <th width="200px" style="text-align:right">Unit Price</th>
                                    <th width="200px" style="text-align:right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->skus as $index => $sku)
                                <tr style="">
                                    <td><span>{{ $sku->item->name }} {{ $sku->data ? '('.$sku->data.')' : '' }}</span></td>
                                    <td>{{ $sku->pivot->qty }}</td>
                                    <td style="text-align:right">{{ number_format($sku->pivot->price) }}</td>
                                    <td class="" style="text-align:right">{{ number_format($sku->pivot->qty * $sku->pivot->price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="" style="">

                            </tfoot>
                        </table>
                        <table class="mb-3">
                            <tr class="small" style="text-align:right">
                                <th width="600px"></th>
                                <th></th>
                                <th width="200px" class="" style="text-align:right">Total</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->price) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th width="600px"></th>
                                <th></th>
                                <th width="200px" class="" style="text-align:right">Discount</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->discount) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th width="600px"></th>
                                <th></th>
                                <th width="200px" class="" style="text-align:right">Deli Fee</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->deli_fee) }}</th>
                            </tr>
                            <tr class="small" style="text-align:right">
                                <th width="600px"></th>
                                <th></th>
                                <th width="200px" class="" style="text-align:right">Net Amount</th>
                                <th width="200px" class="" style="text-align:right">{{ number_format($order->price - $order->discount + $order->deli_fee) }}</th>
                            </tr>
                        </table>
                    </div>
                    <p class="text-center small pt-2" style="border-top:1px dashed #ddd">** Thanks for shopping with us. **</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
