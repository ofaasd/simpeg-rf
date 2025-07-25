@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{asset(mix('assets/vendor/css/pages/page-auth.css'))}}">
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Section -->
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 p-0">
      <div class="w-100 h-100">
        <img src="{{ asset('assets/img/illustrations/auth-cover-login-mask-' . $configData['style'] . '.png') }}"
             class="w-100 h-100"
             style="object-fit: cover;"
             alt="mask"
             data-app-light-img="illustrations/auth-cover-login-mask-light.png"
             data-app-dark-img="illustrations/auth-cover-login-mask-dark.png" />
      </div>
    </div>
    <!-- /Left Section -->

    <!-- Login -->
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
      <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <a href="{{url('/')}}" class="auth-cover-brand d-flex align-items-center gap-2">
          <span class="app-brand-logo demo"><img src="https://payment.ppatq-rf.id/assets/images/logo.png" alt="" height="50"></span>
          <span class="app-brand-text demo text-heading fw-bold">{{config('variables.templateName')}}</span>
        </a><br />
        {{-- <h4 class="mb-2 fw-semibold">Welcome to SIMPEG {{config('variables.templateName')}}! 👋</h4> --}}
        <h5 class="mb-2 fw-semibold">Selamat Datang di Sistem Informasi Manajemen Pengelolaan Pondok Pesantren Anak Raudlatul Falah! 👋</h5>
        <p class="mb-4">Please sign-in to your account and start the adventure</p>

        @if (session('status'))
        <div class="alert alert-success mb-3 rounded" role="alert">
          <div class="alert-body">
            {{ session('status') }}
          </div>
        </div>
        @endif

        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
          @csrf
          <div class="form-floating form-floating-outline mb-3">
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" placeholder="john@example.com" autofocus value="{{ old('email') }}">
            <label for="login-email">Email</label>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="mb-3">
            <div class="form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="password" id="login-password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                  <label for="login-password">Password</label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
          </div>
          <div class="mb-3 d-flex justify-content-between">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember-me">
                Remember Me
              </label>
            </div>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="float-end mb-1">
              <span>Forgot Password?</span>
            </a>
            @endif
          </div>
          <button class="btn btn-primary d-grid w-100">
            Sign in
          </button>
        </form>

        <!-- <p class="text-center mt-2">
          <span>New on our platform?</span>
          @if (Route::has('register'))
          <a href="{{ route('register') }}">
            <span>Create an account</span>
          </a>
          @endif
        </p>

        <div class="divider my-4">
          <div class="divider-text">or</div>
        </div>

        <div class="d-flex justify-content-center gap-2">
          <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-facebook">
            <i class="tf-icons mdi mdi-24px mdi-facebook"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-twitter">
            <i class="tf-icons mdi mdi-24px mdi-twitter"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-github">
            <i class="tf-icons mdi mdi-24px mdi-github"></i>
          </a>

          <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-google-plus">
            <i class="tf-icons mdi mdi-24px mdi-google"></i>
          </a>
        </div> -->
      </div>
    </div>
    <!-- /Login -->
  </div>
</div>
@endsection
