@extends('layouts.admin')

@section('title', 'Total Print')

@section('classes', 'admin admin-total-print')

@section('content')

<div>
    <h2 class="page-title text-capitalize">Total Expenses Report - {{ $month->format('M ,Y') }}</h2>

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
            @foreach($data as $type)
            @php($ty = App\Models\Type::find($type['id']))
            @if($amount = $ty->getExpenseTotal($start, $end))
            @php($total += $amount)
            <tr>
                <td>{{ ++$count }}</td>
                <td>{{ $type['name'] }}</td>
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

@endsection

@section('scripts')
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
