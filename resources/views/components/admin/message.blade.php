@if(session('message') && !session('error'))
<div class="alert alert-success alert-dismissible fade show mb-2">
    <h6 class="mb-0">{{ session('message') }}</h6>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-2">
    <h6 class="mb-0">{{ session('error') }}</h6>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul class="m-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif