@extends('layouts.site.app')

@section('title', 'Reset Password')

@section('classes', 'login-page')

@section('content')
<div class="container overflow-auto" style="height: 100vh;">
    <div class="py-3">
        <a href="{{ route('home') }}"><i class="fa fa-angle-left mr-2"></i>Back</a>
    </div>
    <div class="row justify-content-center" style="height: calc(100vh - 60px)">
        <div class="col-md-4">
            <div class="login-wrapper">                
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="text-center mb-2">
                            <span class="shadow logo"><img src="{{ asset('images/test/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 65px"></span>
                        </div>
                        
                        <h4 class="mb-4 text-center text-secondary">{{ __('Reset Password') }}</h4>

                        <div class="form-group">
                            <div class="">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">                          

                            <div class="">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <button type="submit" class="btn form-control btn-secondary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
