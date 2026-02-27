@php($siteName = \App\Models\Setting::get('site_name', config('app.name')))
@php($siteLogo = \App\Models\Setting::get('site_logo'))
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title') - {{ $siteName }}</title>
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
    .auth-card{max-width:520px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:20px;box-shadow:var(--shadow);padding:28px;}
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
    </div>
  </div>
</header>

<main class="container py-4">
  <div class="auth-card">
    <h3 class="mb-3">@yield('heading')</h3>
    @yield('content')
  </div>
</main>
</body>
</html>
