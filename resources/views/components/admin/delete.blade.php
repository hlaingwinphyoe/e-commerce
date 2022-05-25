<div class="modal fade" id="delete-modal-{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-end border-0 pb-0">
                <button type="button" class="close btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-danger"></i></h1>
                </div>
                <h6>သင်ဒါကို ဖျက်မှာ သေချာပါသလား?</h6>
                <p class="text-muted small">(ဒါနှင့်ဆက်စပ်နေသော Stock, Order များ ရှိခဲ့ပါက အကုန်ပျက်ပါလိမ့်မည်။ ဖျက်ပြီးလျှင် ပြန်ယူ၍ မရတော့ပါ။)</p>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">မဖျက်တော့ပါ</button>
                <form action="{{ $url }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-danger">ဖျက်မည်</button>
                </form>
            </div>
        </div>
    </div>
</div>
