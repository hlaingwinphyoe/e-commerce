<div class="modal fade" id="delete-all-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-end border-0 pb-0">
                <button type="button" class="btn-closer" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-danger"></i></h1>
                </div>
                <h6>သင်ဒါကို ဖျက်မှာ သေချာပါသလား?</h6>
                <p class="text-muted small">(ဖျက်ပြီးလျှင် ပြန်ယူ၍ မရတော့ပါ။)</p>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">မဖျက်တော့ပါ</button>
                <button class="btn btn-danger" data-url="{{ $url }}" id="delete-all">ဖျက်မည်</button>
            </div>
        </div>
    </div>
</div>
