@extends('layouts.admin')

@section('title', 'Sales')

@section('classes', 'admin admin-deliveries admin-deliveries-index')

@section('content-header')
<div class="d-flex content-header">
    <x-admin.content-header :navs="['deliveries']"></x-admin.content-header>
    <form action="{{ route('admin.deliveries.index') }}" class="d-flex align-items-center px-2">
        <div class="input-group mb-2">        
            <input type="text" name="order_no" class="form-control form-control-sm" placeholder="Search with sale no." value="{{ request('order_no') }}">
            <div class="input-group-prepend">
            <button type="submit" class="btn btn-sm btn-primary">
                <small><i class="fa fa-search"></i></small>
            </button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('content')

<div>
   
    <div class="d-flex mb-4">
        <h4 class="page-title mb-0 me-2">{{$delivery->name}} ({{$delivery->phone}})</h4>
       
    </div>
    <div class="d-flex responsive-flex mb-4">        
        
        
        <form action="{{ route('admin.deliveries.show',$delivery->id) }}" class="d-flex responsive-flex">
            <div class="form-group me-2">
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="form-group me-2">
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-dark me-2">Filter</button>
                <a href="{{ route('admin.deliveries.show',$delivery->id) }}" class="btn btn-sm btn-primary">
                    <small><i class="fa fa-redo"></i></small>
                </a>
            </div>
        </form>
        
    </div>

    @include('components.admin.message')

    <div class="card border-0 col-md-8">
        <div class="card-body">
           @if (request()->has('from_date') && request()->has('to_date'))
            <p class="text-muted mm-font"><em class="mx-1 text-danger">[ {{request()->get('from_date') }} &nbsp; {{request()->get('to_date')}} ]</em> အတွက်  Order စာရင်း။</p>
         
           @else
            <p class="text-muted mm-font">ယနေ့အတွက် Order စာရင်း။</p>
           @endif
              
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="">
                        <tr>
                           
                            <th>Order_No</th>
                            <th>Customer</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{$order->order_no}}</td>
                                <td>
                                    <p class="mb-0">{{ $order->customer->name }}</p>
                                    <div class="text-danger small">{{ $order->customer->phone }}</div>
                                </td>
                                <td class="text-end">{{ number_format($order->price) }}</td>                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">There is no data still yet!</td>
                            </tr>
                        @endforelse
                        
                        @if ($total_sum)
                            <tr>
                                <th colspan="2">Total </th>
                                <th class="text-end">
                                {{ number_format($total_sum) }}
                                </th>
                            </tr>
                        @endif
                            
                            
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    
    
</div>

@endsection
