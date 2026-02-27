@extends('auth.layout')
@section('title','Confirm Password')
@section('heading','Confirm your password')

@section('content')
<p class="text-muted small mb-3">
  This is a secure area. Please confirm your password to continue.
</p>

<form method="POST" action="{{ route('password.confirm') }}">
  @csrf

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control" required autocomplete="current-password">
    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="d-flex align-items-center justify-content-between">
    <a class="small text-decoration-none" href="{{ route('login') }}">Back to login</a>
    <button class="btn btn-primary">Confirm</button>
  </div>
</form>
@endsection
