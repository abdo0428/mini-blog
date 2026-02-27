@extends('auth.layout')
@section('title','Login')
@section('heading','Welcome back')

@section('content')
@if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
  @csrf

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control" required autocomplete="current-password">
    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3 form-check">
    <input class="form-check-input" type="checkbox" name="remember" id="remember">
    <label class="form-check-label" for="remember">Remember me</label>
  </div>

  <div class="d-flex align-items-center justify-content-between">
    @if (Route::has('password.request'))
      <a class="small text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
    @endif
    <button class="btn btn-primary">Log in</button>
  </div>

  <div class="text-muted small mt-3">
    New here? <a href="{{ route('register') }}">Create an account</a>
  </div>
</form>
@endsection
