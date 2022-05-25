@extends('layouts.site.app')

@section('title', 'Confirm Password')

@section('classes', 'login-page')

@section('content')
<div class="container">
    <div class="py-3">
        <a href="{{ route('home') }}"><i class="fa fa-angle-left mr-2"></i>Back</a>
    </div>
    <div class="row align-items-center justify-content-center" style="height: calc(100vh - 60px)">
        <div class="col-md-4">
            <div class="login-wrapper">                   

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <input type="hidden" name="recaptcha_v3" id="recaptcha">

                        <div class="text-center mb-2">
                            <span class="shadow logo"><img src="{{ asset('images/test/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 65px"></span>
                        </div>
                        
                        <h4 class="mb-4 text-center text-secondary">{{ __('Confirm Password') }}</h4>

                        <p>{{ __('Please confirm your password before continuing.') }}</p>

                        <div class="form-group">                            

                            <div class="">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <button type="submit" class="form-control btn btn-secondary">
                                    {{ __('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
