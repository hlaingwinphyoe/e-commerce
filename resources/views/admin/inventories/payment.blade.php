<div class="modal fade" id="payment-modal-{{ $inventory->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-end border-0 pb-0">
                <button type="button" class="btn btn-sm btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fw-bold"><i class="fa fa-times fa-lg"></i></span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exchange-alt text-info"></i></h1>
                </div>
                <h4>Purchase အတွက် ငွေပေးချေမည်...</h4>
            </div>
            <div class="modal-footer d-block border-0">

                <form action="{{ route('admin.inventory-transactions.store') }}" method="post">
                    @csrf
                    @method('post')

                    <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">

                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="text" name="amount" class="form-control form-control-sm" placeholder="Amount" value="{{ $inventory->getBalance() }}" required>
                    </div>

                    <div class="form-group">
                        <label for="">Payment Type</label>
                        <select name="paymentype_id" class="form-select">
                            @foreach($payment_statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="date" name="date" class="form-control form-control-sm" placeholder="Date" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="">Next Payment Date</label>
                        <input type="date" name="next_date" class="form-control form-control-sm" placeholder="Date">
                    </div>

                    <div class="form-group">
                        <label for="">Remark</label>
                        <textarea name="remark" class="form-control form-control-sm" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary">Make Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>