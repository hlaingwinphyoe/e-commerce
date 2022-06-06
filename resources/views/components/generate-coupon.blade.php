<div class="modal fade" id="generate-coupon-form" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="close btn btn-sm btn-outline-danger fw-bold shadow" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.coupons.generate') }}" method="post">
                    @csrf

                    <div>
                        <h5 class="text-danger mb-3">Coupon Information</h5>
                        <div class="form-group">
                            <label for="">Count</label>
                            <small class="help-text text-muted">ထုတ်မည့်အရေအတွက် ထည့်ပါ။</small>
                            <input type="text" name="count" class="form-control form-control-sm" placeholder="Count" value="{{ old('count') }}">
                        </div>

                        <div class="form-group">
                            <label for="">
                                Amount
                                <span class="text-danger">**</span>
                            </label>
                            <small class="help-text text-muted">Amount ထည့်ပါ။ တူ၍မရပါ။</small>
                            <div class="input-group">
                                <input type="text" name="amt" class="form-control form-control-sm" placeholder="Discount Amount" value="{{ old('amt') }}">
                                <div class="input-group-prepend">
                                    <select name="type" class="form-select form-select-sm">
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percent</option>
                                    </select>
                                </div>
                            </div>
                            @error('amt')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group d-none">
                            <label for="">
                                Type
                                <span class="text-danger">**</span>
                            </label>
                            <small class="help-text text-muted">Type ရွေးပါ။ မဖြစ်မနေထည့်ပါ။</small>
                            <select name="type_id" class="form-select form-select-sm">
                                @foreach($maintypes as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('type_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">
                                Expired Date
                            </label>
                            <small class="help-text text-muted">Date ရွေးပါ။ ရှိလျှင်ထည့်ပါ။</small>
                            <input type="date" class="form-control form-control-sm" name="expired" placeholder="Expired Date" value="{{ old('expired') }}">
                            @error('expired')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="from-group">
                        <button type="submit" class="btn btn-sm btn-secondary">
                            <small class="mr-2"><i class="fas fa-save"></i></small>
                            <span>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>