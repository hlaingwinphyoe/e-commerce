<div class="modal fade" id="restore-modal-{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-warning"></i></h1>
                </div>
                <h4>Are you sure you want to restore this?</h4>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ $url }}" method="post">
                    @csrf
                    @method('patch')
                    <button type="submit" class="btn btn-sm btn-info">Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>
