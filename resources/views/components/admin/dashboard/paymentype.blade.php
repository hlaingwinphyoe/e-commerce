<div class="bg-sidebar rounded p-3 h-100">
    <a href="{{ route('admin.transactions.index') }}" class="float-end small text-secondary">More Detials</a>
    <h5>Payment Transactions for this month.</h5>
    <ul class="nav flex-column">
        @foreach($paymentypes as $paymentype)
        <li class="nav-item mb-3">
            <span class="text-uppercase text-primary-dark">{{ $paymentype->name }}</span>
            <div class="row">
                <div class="col-6">
                    <p class="mb-0 text-muted small">Total Transactions (IN) - {{ $paymentype->getTotalTransactionsByMonth()['qty'] }}</p>
                    <p class="mb-0 text-muted small">Total Amount - {{ number_format($paymentype->getTotalTransactionsByMonth()['total'],2) }}</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-success" style="width: {{ $total_in_count ? ($paymentype->getTotalTransactionsByMonth()['qty']/$total_in_count)*100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="col-6">
                    <p class="mb-0 text-muted small">Total Transactions (OUT) - {{ $paymentype->getTotalTransactionsByMonth('out')['qty'] }}</p>
                    <p class="mb-0 text-muted small">Total Amount - {{ number_format($paymentype->getTotalTransactionsByMonth('out')['total'],2) }}</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-danger" style="width: {{ $total_out_count ? ($paymentype->getTotalTransactionsByMonth('out')['qty']/$total_out_count)*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

        </li>
        @endforeach
    </ul>
</div>