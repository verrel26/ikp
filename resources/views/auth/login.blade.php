@extends('layouts.header')
<section id="contact" class="contact">
    <div class="container">

        <div class="section-title">
            <h2>Login</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="{{ route('login') }}" method="POSt" role="form" class="php-email-form">
                    @csrf
                    <div class="row">

                        <div class="form-group col-md-12 my-2 ">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror mb-2" id="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row mb-0">
                        <div class="col-md-4 offset-md-4">
                            <div class="text-center">
                                <button type="submit" class="text-center">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <br>
                            @if (Route::has('password.request'))
                                <a class="text-center mb-3 " href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                        <a href="/" class="text-center justify-content-center">Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
