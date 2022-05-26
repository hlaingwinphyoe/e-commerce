<?php $inventory = \App\Models\Inventory::find($id); ?>
<div class="modal fade" id="publish-item-{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-end border-0 pb-0">
                <button type="button" class="close btn btn-sm btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span><i class="fa fa-times fa-lg"></i></span>
                </button>
            </div>
            @if($inventory->skus->count())
            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-danger"></i></h1>
                </div>
                <h6>" {{ $inventory->supplier ? $inventory->supplier->name : '' }} " မှ မှာယူထားသောစာရင်းများ ရောက်ရှိတာ သေချာပါသလား?</h6>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">မသေချာပါ</button>
                <form action="{{ route('admin.inventories.publishById',$id)}}" method="post">
                    @csrf
                    @method('patch')
                    <button type="submit" class="btn btn-sm btn-danger">ရောက်ရှိသည်</button>
                </form>
            </div>
            @else

            <div class="modal-body text-center">
                <div class="text-center">
                    <h1><i class="fas fa-exclamation-circle text-danger"></i></h1>
                </div>
                <h6>စာရင်းများ ထည့်သွင်းထားခြင်း မရှိသေးပါ။ </h6>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">မလုပ်တော့ပါ</button>
                <a href="{{ route('admin.inventories.edit', $id) }}" class="btn btn-sm btn-danger">စာရင်းထည့်မည်</a>
            </div>

            @endif
        </div>
    </div>
</div>