<div class="modal fade" id="return-modal-{{ $order->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-danger"></i></h1>
                </div>
                <h4>Are you sure you want to create return for this order?</h4>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('admin.returns.create') }}?order_id={{ $order->id }}" class="btn btn-sm btn-info">Yes create it.</a>
            </div>
        </div>
    </div>
</div>
