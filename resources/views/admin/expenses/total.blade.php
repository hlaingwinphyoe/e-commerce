@extends('layouts.admin')

@section('title', 'Total Expenses')

@section('classes', 'admin admin-total-expenses')

@section('content')

<div>
    <h2 class="page-title text-capitalize">Total Expenses</h2>
</div>

<form action="{{ route('admin.expense.total') }}">
    <div class="d-flex align-items-center">
        <div class="form-group me-2">
            <select name="month" class="form-select">
                @foreach($period as $month)
                <option value="{{ $month->format('M, Y') }}" {{ request('month') == $month->format('M, Y') ? 'selected' : '' }}>{{ $month->format('M, Y') }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary me-2">Apply</button>
            <a href="{{ route('admin.expense.total.print') }}?month={{ request('month') }}&data={{ $types }}&start={{ $start }}&end={{ $end }}" class="btn btn-secondary">
                <i class="fa-solid fa-print"></i> Print
            </a>
        </div>
    </div>
</form>

<div class="py-4 row">
    <div class="col-md-5">
        <div class="border shadow px-3 py-4">
            <h4 class="mb-4"><i class="fa-solid fa-list-check"></i> Lists</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                @php($total = $count = 0)
                <tbody>
                    @foreach($types as $type)
                    @if($amount = $type->getExpenseTotal($start, $end))
                    @php($total += $amount)
                    <tr>
                        <td>{{ ++$count }}</td>
                        <td>{{ $type->name }}</td>
                        <td class="text-end">{{ number_format($amount) }}</td>
                    </tr>
                    @endif
                    @endforeach

                    @if($count < 1)
                    <tr>
                        <td colspan="3">There is no expense for this month.</td>
                    </tr>
                    @endif
                </tbody>
                @if($total > 0)
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-success fw-bold">Total</th>
                        <th class="text-end text-success fw-bold">{{ number_format($total) }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>


@endsection
