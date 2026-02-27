<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Admin')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css" rel="stylesheet">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{--admin-brand:#0f766e;--admin-ink:#0f172a;--admin-muted:#64748b;}
    body{background:linear-gradient(180deg,#eef2f7 0%,#f8fafc 60%,#ffffff 100%);}
    .navbar{box-shadow:0 6px 18px rgba(2,6,23,.15);}
    .quick-admin{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:12px;box-shadow:0 8px 18px rgba(2,6,23,.06);}
    .quick-admin .btn{border-radius:999px;font-weight:600;}
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    @php($siteName = \App\Models\Setting::get('site_name', config('app.name')))
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">{{ $siteName }}</a>
    <div class="d-flex gap-2">
      @role('admin')
        <a class="btn btn-outline-light btn-sm" href="{{ route('admin.settings.index') }}">Settings</a>
      @endrole
      <a class="btn btn-outline-light btn-sm" href="{{ route('profile.edit') }}">Profile</a>
      <a class="btn btn-outline-light btn-sm" href="{{ route('blog.index') }}" target="_blank">View Site</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-danger btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="quick-admin mb-3 d-flex flex-wrap gap-2 align-items-center justify-content-between">
    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.posts.index') }}">Posts</a>
      @role('admin')
        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.categories.index') }}">Categories</a>
        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.tags.index') }}">Tags</a>
        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.settings.index') }}">Settings</a>
      @endrole
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-primary btn-sm" href="{{ route('admin.posts.create') }}">+ New Post</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('profile.edit') }}">Edit Profile</a>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('blog.index') }}" target="_blank">Public Site</a>
    </div>
  </div>

  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
  });

  const toastMsg = @json(session('success'));
  if (toastMsg) {
    Swal.fire({
      toast: true,
      position: 'top-start',
      icon: 'success',
      title: toastMsg,
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true
    });
  }
</script>

@stack('scripts')
</body>
</html>
