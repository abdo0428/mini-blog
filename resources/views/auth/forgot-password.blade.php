@extends('auth.layout')
@section('title','Forgot Password')
@section('heading','Reset your password')

@section('content')
<p class="text-muted small mb-3">
  Enter your email address and we will send you a password reset link.
</p>

@if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
  @csrf

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="d-flex align-items-center justify-content-between">
    <a class="small text-decoration-none" href="{{ route('login') }}">Back to login</a>
    <button class="btn btn-primary">Email reset link</button>
  </div>
</form>
@endsection
