<div class="modal" tabindex="-1" id="home-feature-{{ $home_feature->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Home Features</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.mainfeatures.update', $home_feature->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Title" value="{{ $home_feature->title }}">
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Link</label>
                        <input type="text" name="link" class="form-control form-control-sm" placeholder="Link" value="{{ $home_feature->link }}">
                        @error('link')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <media-upload :images="{{ $home_feature->medias()->pluck('id') }}" type="home-features"></media-upload>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
