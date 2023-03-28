@extends('layouts.admin')

@section('title', 'Report Summary')

@section('classes', 'admin admin-reports admin-reports-index')


@section('content')

<div>
    <h4 class="page-title text-primary {{App::getLocale() == 'mm' ? 'mm-font' : ''}}">{{__('menu.summary')}}</h4>
    <h6 class="fw-bold">Total Report for this month</h6>

    <!-- filter -->
    <form action="{{ route('admin.reports.summary') }}" method="get" class="d-flex pt-3">
        <div class="form-group me-2">
            <input type="date" class="form-control form-control-sm" placeholder="Start Date" value="{{ $start_date->format('Y-m-d') }}" name="from_date">
        </div>

        <div class="form-group me-2">
            <input type="date" class="form-control form-control-sm" placeholder="End Date" value="{{ $end_date->format('Y-m-d') }}" name="to_date">
        </div>

        <div class="form-group me-2">
            <button type="submit" class="btn btn-sm btn-outline-primary me-2">Filter</button>
            <a href="{{ route('admin.reports.summary') }}" class="btn btn-sm btn-primary"><i class="fa fa-redo"></i></a>
        </div>
    </form>
</div>

<div class="row py-4">
    <div class="col-md-9">
        <div class="row align-items-center">
            <div class="col-6 col-md-4 mb-3">
                <div class="px-3 py-4 bg-sidebar text-center">
                    <p class="h5 text-muted mb-4">Total Sales</p>
                    <h3 class="fw-bold text-primary">{{ number_format($sales_data['total']) }}</h3>
                </div>
            </div>

            <div class="col-6 col-md-4 mb-3">
                <div class="px-3 py-4 bg-sidebar text-center">
                    <p class="h5 text-muted mb-4">Total Purchase</p>
                    <h3 class="fw-bold text-primary">{{ number_format($purchase_data['total']) }}</h3>
                </div>
            </div>

            <div class="col-6 col-md-4 mb-3">
                <div class="px-3 py-4 bg-sidebar text-center">
                    <p class="h5 text-muted mb-4">Total Expense</p>
                    <h3 class="fw-bold text-primary">{{ number_format($expense_total) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="bg-sidebar px-4 py-3 h-100">
            <h5 class="text-primary page-title">Inventory</h5>
            <p class="text-uppercase">Total Inventory Values</p>
            <h1 class="fw-bold text-secondary">{{ number_format($stock_values) }}</h1>
            <p class="small text-muted">** လက်ကျန်ရှိနေသော ပစ္စည်းများ၏ စုစုပေါင်းတန်ဖိုးဖြစ်ပါသည်။</p>
            <a href="{{ route('admin.skus.index') }}?status=instock" target="_blank" class="btn btn-sm btn-primary w-100 mb-3">လက်ကျန် ကုန်စာရင်းများကြည့်မည်</a>
            <a href="{{ route('admin.skus.index') }}?status=outofstock" target="_blank" class="btn btn-sm btn-outline-secondary w-100 mb-3">ထပ်ဖြည့်ရမည့် ကုန်စာရင်းများကြည့်မည်</a>
        </div>
    </div>
</div>
@endsection