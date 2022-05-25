<div class="row mb-3">
    <form action="{{ $url }}" class="col-md-8 d-flex align-items-center px-2">
        <div class="input-group mb-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search with code or name" value="{{ request('q') }}">
            <div class="input-group-text bg-secondary">
                <button type="submit" class="p-0 border-0 bg-transparent">
                    <small class="text-white"><i class="fa fa-search"></i></small>
                </button>
            </div>
        </div>
    </form>
</div>