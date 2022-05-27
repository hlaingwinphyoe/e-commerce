@extends('layouts.admin')

@section('title', 'Slides')

@section('classes', 'admin admin-slides admin-slides-index')

@section('content-header')

<div class="d-flex content-header">
    <x-admin.content-header :navs="['slides']"></x-admin.content-header>
</div>
@endsection

@section('content')

<div>

    <ul class="nav site-nav-tabs" id="slide-nav">
        <li class="nav-item">
            <a href="{{ route('admin.slides.index') }}" class="nav-link active">Slides</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.mainfeatures.index') }}" class="nav-link">Features</a>
        </li>
    </ul>

    <!-- Slide Image Tab -->
    <div class="py-4">
        <div class="d-flex mb-4">
            <h4 class="page-title mb-0 me-2">Slide Images</h4>
            <span class="text-muted form-text">( Showing {{ $slides->count() }} of total {{ $slides->count() }} records )</span>
        </div>
        @include('components.admin.message')
        <div class="slide-box">
            <p class="mm-font">
                အောက်ပါပုံများသည် Home Page Slides နေရာများတွင် ပြသပါမည်။ ပုံ size များတူလျှင် ပိုကောင်းပါသည်။ အကောင်းဆုံး ပုံ size မှာ 
                <span class="bg-light border text-primary">1920 x 350 pixels</span> ဖြစ်ပါသည်။
            </p>
            <media-upload :images="{{ $slides->pluck('id') }}" type="slides"></media-upload>
        </div>
    </div>
    <!-- Slide Image Tab -->
</div>
@endsection
