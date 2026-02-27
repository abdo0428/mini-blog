@extends('auth.layout')
@section('title','Reset Password')
@section('heading','Choose a new password')

@section('content')
<form method="POST" action="{{ route('password.store') }}">
  @csrf

  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
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
    <a class="small text-decoration-none" href="{{ route('login') }}">Back to login</a>
    <button class="btn btn-primary">Reset password</button>
  </div>
</form>
@endsection
