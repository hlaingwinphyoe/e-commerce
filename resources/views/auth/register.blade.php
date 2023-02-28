<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ env('APP_URL') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <title>Login</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {!! RecaptchaV3::initJs() !!}

</head>

<body class="auth" id="home">

    <div class="container">
        <div class="back-nav container pt-2">
            <ul class="nav row">
                <li class="nav-item">
                    <a href="{{ request()->session()->get('prev_route') }}" class="nav-link text-primary fw-bold ps-0">
                        <span class="me-1"><i class="fa fa-arrow-alt-circle-left"></i></span>
                        <span>Back</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row align-items-center justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-wrapper bg-white shadow rounded rounded-3 px-3 py-2">
                    <form class="login100-form" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="text-center mb-3 mt-3">
                            <span><img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 45px"></span>
                            <h5 class="d-inline">{{ config('app.name') }}</h5>
                        </div>

                        <p class="text-center mb-4 mm-font small">{{ config('app.name') }} မှကြိုဆိုပါတယ်။ ကျေးဇူးပြု၍အကောင့်သို့၀င်ပါ။</p>

                        <div class="form-group mb-3 ">
                            <input type="text" placeholder="အမည်" class="mm-font form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <span class="focus-input100"></span>
                        </div>
                        @error('name')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="form-group mb-3 ">
                            <input type="text" placeholder="ဖုန်းနံပါတ်" class="mm-font form-control  @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                            <span class="focus-input100"></span>
                        </div>
                        @error('phone')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="form-group mb-3 ">
                            <input type="email" placeholder="အီးမေးလ်" class="mm-font form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                            <span class="focus-input100"></span>
                        </div>
                        @error('email')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="form-group mb-3 ">
                            <input type="password" placeholder="စကားဝှက်" class="mm-font form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="new-password">
                            <span class="focus-input100"></span>
                        </div>
                        @error('password')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror


                        <div class="form-group mb-3 ">
                            <input type="password" name="password_confirmation" placeholder="စကားဝှက် အတည်ပြုရန်" class="mm-font form-control" required autocomplete="new-password">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="form-group mb-3 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                {!! RecaptchaV3::field('register') !!}
                                @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group mt-3 mb-4">
                            <button class="btn btn-primary text-white w-100" type="submit">အကောင့်ပြုလုပ်မည်</button>
                        </div>

                        <p class="text-center text-uppercase mb-2 fw-bold d-none">Or</p>

                        <ul class="nav align-items-center justify-content-center w-100 pb-3 d-none">
                            <li class="nav-item w-100 bg-primary rounded rounded-3 mb-2 text-center">
                                <a href="/facebook/login" class="nav-link text-white">
                                    <i class="fab fa-facebook fa-2x"></i>
                                    <span class="ms-2">Facebook အကောင့်ဖြင့်ဝင်မည်</span>
                                </a>
                            </li>
                            <li class="nav-item w-100 bg-primary rounded rounded-3 text-center">
                                <a href="/google/login" class="nav-link text-white">
                                    <i class="fab fa-google-plus fa-2x"></i>
                                    <span class="ms-2">Google အကောင့်ဖြင့်ဝင်မည်</span>
                                </a>
                            </li>
                        </ul>

                        <p class="text-center mm-font">အကောင့်ရှိပါသလား? <a href="{{ route('login') }}" class="text-primary-dark mm-font">ဒါကိုနှိပ်ပါ။</a></p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
