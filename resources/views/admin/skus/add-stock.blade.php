<!-- Modal -->
<div class="modal fade" id="add-stock-{{ $sku->id }}" tabindex="-1" aria-labelledby="edit_amount_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_amount_label">Add Stock for {{ $sku->item ? $sku->item->name : '' }} {{ $sku->data ? $sku->data : '' }} </h5>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.skus.add-stock', $sku->id) }}" method="post">
                @csrf

                <div class="modal-body">

                    <div class="form-group">
                        <label for="">ထည့်မည့်အရေအတွက်</label>
                        <input type="text" name="qty" placeholder="Qty" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="">ဝယ်ဈေး (optional)</label>
                        <input type="text" name="buy_price" placeholder="Buy Price" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Add Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>