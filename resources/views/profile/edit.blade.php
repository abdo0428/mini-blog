@php($siteName = \App\Models\Setting::get('site_name', config('app.name')))
@php($siteLogo = \App\Models\Setting::get('site_logo'))
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profile - {{ $siteName }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{--ink:#0f172a;--muted:#64748b;--brand:#0f766e;--border:#e2e8f0;--shadow:0 10px 30px rgba(2,6,23,.08);}
    body{font-family:"Space Grotesk",system-ui,-apple-system,Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,#eef2f7 0%,#f8fafc 35%,#ffffff 100%);color:var(--ink);}
    .site-header{position:sticky;top:0;z-index:20;background:rgba(255,255,255,.82);backdrop-filter:saturate(140%) blur(12px);border-bottom:1px solid var(--border);}
    .brand{display:flex;align-items:center;gap:12px;font-weight:700;}
    .brand img{height:34px;width:auto;border-radius:8px;}
    .card{border-radius:20px;border:1px solid var(--border);box-shadow:var(--shadow);}
    .btn{border-radius:999px;font-weight:600;}
    .btn-primary{background:var(--brand);border-color:var(--brand);}
    .btn-primary:hover{background:#0ea5a4;border-color:#0ea5a4;}
  </style>
</head>
<body>
<header class="site-header">
  <div class="container py-3 d-flex align-items-center justify-content-between gap-3 flex-wrap">
    <div class="brand">
      @if($siteLogo)
        <img src="{{ asset('storage/'.$siteLogo) }}" alt="{{ $siteName }}" loading="lazy">
      @endif
      <span>{{ $siteName }}</span>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('blog.index') }}">Home</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
    </div>
  </div>
</header>

<main class="container py-4">
  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">Profile Information</h5>
          <p class="text-muted small">Update your name and email address.</p>

          <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
          </form>

          <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
              <label class="form-label">Name</label>
              <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
              @error('email') <div class="text-danger small">{{ $message }}</div> @enderror

              @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="small mt-2">
                  <span class="text-muted">Your email address is unverified.</span>
                  <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Resend verification email</button>
                </div>
                @if (session('status') === 'verification-link-sent')
                  <div class="text-success small mt-1">A new verification link has been sent to your email.</div>
                @endif
              @endif
            </div>

            <div class="d-flex align-items-center gap-3">
              <button class="btn btn-primary">Save</button>
              @if (session('status') === 'profile-updated')
                <span class="text-muted small">Saved.</span>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">Update Password</h5>
          <p class="text-muted small">Use a strong password you don't use elsewhere.</p>

          <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <input name="current_password" type="password" class="form-control" autocomplete="current-password">
              @if($errors->updatePassword->has('current_password'))
                <div class="text-danger small">{{ $errors->updatePassword->first('current_password') }}</div>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input name="password" type="password" class="form-control" autocomplete="new-password">
              @if($errors->updatePassword->has('password'))
                <div class="text-danger small">{{ $errors->updatePassword->first('password') }}</div>
              @endif
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
              @if($errors->updatePassword->has('password_confirmation'))
                <div class="text-danger small">{{ $errors->updatePassword->first('password_confirmation') }}</div>
              @endif
            </div>

            <div class="d-flex align-items-center gap-3">
              <button class="btn btn-primary">Save</button>
              @if (session('status') === 'password-updated')
                <span class="text-muted small">Saved.</span>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card border-danger">
        <div class="card-body">
          <h5 class="mb-2 text-danger">Delete Account</h5>
          <p class="text-muted small">This will permanently delete your account and all associated data.</p>
          <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Account Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <div class="modal-body">
          <p class="text-muted">Please enter your password to confirm.</p>
          <input name="password" type="password" class="form-control" placeholder="Password">
          @if($errors->userDeletion->has('password'))
            <div class="text-danger small mt-2">{{ $errors->userDeletion->first('password') }}</div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  @if ($errors->userDeletion->isNotEmpty())
    const modalEl = document.getElementById('deleteAccountModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  @endif
</script>
</body>
</html>
