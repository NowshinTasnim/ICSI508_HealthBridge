{{--@extends('layouts.loginlayout')--}}

{{--@section('content')--}}
{{--    <div class="admin_login_form_container fix">--}}
{{--        <div class="admin_login_form_header fix">{{ __('MEMBER LOGIN') }}</div>--}}

{{--        <div class="admin_login_form_body fix">--}}
{{--            <form method="POST" action="{{ route('login.submit') }}">--}}
{{--                @csrf--}}

{{--                <div class="form-group row">--}}
{{--                    <label for="email" class="admin_login_form_left fix">{{ __('E-Mail Address :') }}</label>--}}

{{--                    <div class="col-md-6">--}}
{{--                        <input id="email" type="email" class="admin_login_form_right fix" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                        @error('email')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                    <strong>{{ $message }}</strong>--}}
{{--                    </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="form-group row">--}}
{{--                    <label for="password" class="admin_login_form_left fix">{{ __('Password :') }}</label>--}}

{{--                    <div class="col-md-6">--}}
{{--                        <input id="password" type="password" class="admin_login_form_right fix" name="password" required autocomplete="current-password">--}}

{{--                        @error('password')--}}
{{--                        <span class="invalid-feedback" role="alert">--}}
{{--                        <strong>{{ $message }}</strong>--}}
{{--                    </span>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="form-group row mb-0">--}}
{{--                    <div class="col-md-8 offset-md-4">--}}
{{--                        <button type="submit" class="btn_admin_login fix">--}}
{{--                            {{ __('Login') }}--}}
{{--                        </button>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}


{{--CSRF (Cross-Site Request Forgery) protection is required whenever you handle state-changing requests (e.g., POST, PUT, PATCH, DELETE) to prevent unauthorized actions.--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> <!-- Link to your login.css -->
</head>
<body>
<img src="{{ asset('images/HealthBridgeLogo.png') }}" alt="Logo" class="logo">
<div class="contact-us-box">
    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
    <a href="mailto:snehakhan52@gmail.com?subject=Support%20Request&body=Hi%20Team,%0A%0AI%20need%20help%20with..." class="contact-us-text">Contact</a>
</div>



<div class="container">
    <div class="card">
        {{--        <div class="card-header">--}}
        {{--            Login--}}
        {{--        </div>--}}
        <div class="card-body">
            @if ($errors->has('login_error'))
                <div class="alert alert-danger">
                    {{ $errors->first('login_error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="text" class="form-label">{{ __('Login ID') }}</label>
                    <input type="text" class="form-control" id="Login_ID" name="Login_ID" required>


                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input type="password" class="form-control" id="Log_Password" name="Log_Password" required>

                </div>

{{--                <!-- Error message section -->--}}
{{--                @if ($errors->has('login_error'))--}}
{{--                    <div class="alert alert-danger">--}}
{{--                        {{ $errors->first('login_error') }}--}}
{{--                    </div>--}}
{{--                @endif--}}
                {{--                <div class="mb-3 form-check">--}}
                {{--                    <input type="checkbox" class="form-check-input" id="remember" name="remember">--}}
                {{--                    <label class="form-check-label" for="remember">Remember Me</label>--}}
                {{--                </div>--}}
                {{--                Forget Passwords--}}
{{--                <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                    <div class="form-check">--}}
{{--                        <input type="checkbox" class="form-check-input" id="remember" name="remember">--}}
{{--                        <label class="form-check-label" for="remember">Remember Me</label>--}}
{{--                    </div>--}}
{{--                    <a href="#" class="custom-forgot-password">Forgot Password?</a>--}}
{{--                </div>--}}


                <button type="submit" class="btn custom-btn w-50">{{ __('Login') }}</button>

                    <div class="text-center mt-3">
                        <p class="custom-text">
                            Not registered yet? <a href="{{ route('register') }}" class="custom-signup-link">Create an account</a>
                        </p>
                    </div>
            </form>
        </div>
    </div>
</div>
<footer class="footer">
    <p>&copy; 2024 HealthBridge. All rights reserved.</p>
</footer>
</body>
</html>
