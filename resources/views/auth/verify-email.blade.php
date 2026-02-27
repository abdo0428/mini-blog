@extends('auth.layout')
@section('title','Verify Email')
@section('heading','Verify your email')

@section('content')
<p class="text-muted small mb-3">
  Thanks for signing up! Please verify your email address by clicking the link we sent you.
</p>

@if (session('status') == 'verification-link-sent')
  <div class="alert alert-success">
    A new verification link has been sent to the email address you provided.
  </div>
@endif

<div class="d-flex align-items-center justify-content-between">
  <form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn btn-primary">Resend verification email</button>
  </form>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-outline-secondary">Log out</button>
  </form>
</div>
@endsection
