@extends('layouts.site.app')

@section('title', 'Reset Password')

@section('classes', 'login-page')

@section('content')
<div class="container">
    <div class="py-3">
        <a href="{{ route('home') }}"><i class="fa fa-angle-left mr-2"></i>Back</a>
    </div>
    <div class="row align-items-center justify-content-center" style="height: calc(100vh - 60px)">        
        <div class="col-md-4">
            
                <div class="login-wrapper">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <input type="hidden" name="recaptcha_v3" id="recaptcha">

                        <div class="text-center mb-2">
                            <span class="shadow logo"><img src="{{ asset('images/test/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 65px"></span>
                        </div>
                        
                        <h4 class="mb-4 text-center text-secondary">{{ __('Reset Password') }}</h4>

                        <div class="form-group">

                            <div class="">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="btn btn-secondary form-control">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
