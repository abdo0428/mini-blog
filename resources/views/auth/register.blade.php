@extends('auth.layout')
@section('title','Register')
@section('heading','Create your account')

@section('content')
<form method="POST" action="{{ route('register') }}">
  @csrf

  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" type="text" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name">
    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control" required autocomplete="new-password">
    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
    @error('password_confirmation') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="d-flex align-items-center justify-content-between">
    <a class="small text-decoration-none" href="{{ route('login') }}">Already registered?</a>
    <button class="btn btn-primary">Register</button>
  </div>
</form>
@endsection
