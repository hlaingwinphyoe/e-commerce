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

</head>

<body class="auth" id="home">
    <div class="container">
        <div class="back-nav container py-4">
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
                    <form class="login100-form" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="text-center mb-3 mt-3">
                            <span><img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 45px"></span>
                            <h5 class="d-inline text-primary fw-bold">{{ config('app.name') }}</h5>
                        </div>

                        <p class="text-center mb-4 mm-font small text-secondary">{{ config('app.name') }} မှကြိုဆိုပါတယ်။ ကျေးဇူးပြု၍အကောင့်သို့၀င်ပါ။</p>

                        <div class="form-group mb-3">
                            <label for=""><p class="small mb-2">ဖုန်းနံပါတ်ထည့်ပါ</p></label>
                            <input type="text" placeholder="ဖုန်းနံပါတ်" class="mm-font form-control  @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                            <span class="focus-input100"></span>
                        </div>
                        @error('phone')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="form-group w-100 mb-3">
                            <label for=""><p class="small mb-2">လျှို့ဝှက်နံပါတ်ထည့်ပါ</p></label>
                            <input type="password" name="password" placeholder="စကားဝှက်" class="mm-font form-control  @error('password') is-invalid @enderror" required autocomplete="current-password">
                            <span class="focus-input100"></span>
                        </div>
                        @error('password')
                        <span class="text-danger mb-4" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="d-flex">
                            <div class="form-group w-50 mb-3">
                                <input type="checkbox" class="custom-checkbox mm-font" name="remember" id="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <label for="checkbox" class="mm-font small">အကောင့်မှတ်ထားမည်။</label>
                            </div>
                            <div class="form-group w-50 text-right d-none">
                                <a href="{{ route('password.request') }}" class="txt1 mm-font small">
                                    စကားဝှက်မေ့နေပြီလား?
                                </a>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button class="btn btn-primary text-white w-100" type="submit">အကောင့်သို့ဝင်မည်</button>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
