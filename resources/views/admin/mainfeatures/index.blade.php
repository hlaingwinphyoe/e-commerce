@extends('layouts.admin')

@section('title', 'Main Features')

@section('classes', 'admin admin-mainfeatures admin-mainfeatures-index')

@section('content-header')

<div class="d-flex content-header">
    <x-admin.content-header :navs="['slides']"></x-admin.content-header>
</div>
@endsection

@section('content')

<div>
    <ul class="nav site-nav-tabs">
        <li class="nav-item">
            <a href="{{ route('admin.slides.index') }}" class="nav-link">Slides</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.mainfeatures.index') }}" class="nav-link active">Features</a>
        </li>
    </ul>
    <!-- Features Image Tab -->
    <div class="py-4">
        <div class="row">
            <div class="col-12">
                <p class="mm-font mb-4">
                    အောက်ပါပုံများသည် Home Page Promotion Section နေရာများတွင် ပြသပါမည်။ ပုံ size များတူလျှင် ပိုကောင်းပါသည်။ အကောင်းဆုံး ပုံ size မှာ
                    <span class="bg-light border text-primary">500 x 300 pixels</span> ဖြစ်ပါသည်။
                </p>
            </div>
            <div class="col-md-4 mb-2">
                <div>
                    <h4 class="page-title mb-2">Main Features</h4>
                    @foreach($home_features as $home_feature)
                    <div class="box image-box mb-4">
                        <div class="box-featured">
                            <img src="{{ $home_feature->thumbnail }}" alt="{{ $home_feature->title }}" class="w-100">
                        </div>
                        <div class="box-content flex-column overlay-content justify-content-end {{ $home_feature->disabled ? 'disabled' : '' }}">
                            <div class="w-100 pl-3">
                                <a href="#" class="animate-button responsive-text mb-3">
                                    <span>{{ $home_feature->title }}</span>
                                    <span class="animate-icon"><i class="fa fa-arrow-right"></i></span>
                                </a>
                            </div>
                            <div class="bg-dark w-100">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a href="#home-feature-{{ $home_feature->id }}" class="nav-link" data-bs-toggle="modal">
                                            <small class="me-1"><i class="fa fa-pencil-alt"></i></small>
                                            <span>edit</span>
                                        </a>
                                        @include('admin.mainfeatures.edit')
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.mainfeatures.toggle', $home_feature->id) }}" class="nav-link">
                                            <small class="me-1"><i class="fa fa-eye-slash"></i></small>
                                            <span>{{ $home_feature->disabled ? 'enabled' : 'disabled' }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#delete-modal-{{ $home_feature->id }}" class="nav-link" data-bs-toggle="modal">
                                            <small class="me-1"><i class="fas fa-trash"></i></small>
                                            <span>trash</span>
                                        </a>
                                        <x-admin.delete id="{{ $home_feature->id }}" url="{{ route('admin.mainfeatures.destroy', $home_feature->id) }}"></x-admin.delete>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8 mb-2">
                <div class="py-3 px-4">
                    <h4 class="page-title mb-2">Add Features</h4>
                    <form action="{{ route('admin.mainfeatures.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="Title" value="{{ old('title') }}">
                            @error('title')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Link</label>
                            <input type="text" name="link" class="form-control form-control-sm" placeholder="Link" value="{{ old('link') }}">
                            @error('link')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <media-upload :images="[]" type="home-features"></media-upload>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class="fa fa-save"></i>
                                <span class="ml-2">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Features Image Tab -->
</div>
@endsection