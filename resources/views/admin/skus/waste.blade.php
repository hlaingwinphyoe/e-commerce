<!-- Modal -->
<div class="modal fade" id="add-waste-{{ $sku->id }}" tabindex="-1" aria-labelledby="edit_amount_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_amount_label">Add waste </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sku-wastes.store') }}" method="post">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="sku_id" value="{{ $sku->id }}">

                    <div class="form-group">
                        <label for="">Waste Type</label>
                        <select name="status_id" class="form-select">
                            @foreach($statuses as $status)
                            @if($status->slug != 'reset' && $status->slug != 'adjust')
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">
                            Qty
                            <span class="text-danger">**</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" placeholder="Qty" name="amt" required/>
                        @error('amt')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="date" class="form-control form-control-sm" name="date">
                    </div>

                    <div class="form-group">
                        <label for="">Remark</label>
                        <textarea name="remark" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
