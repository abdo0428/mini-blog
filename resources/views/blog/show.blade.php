<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $post->title }} - {{ $siteName }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{
      --ink:#0f172a;
      --muted:#64748b;
      --brand:#0f766e;
      --brand-2:#0ea5a4;
      --paper:#f8fafc;
      --card:#ffffff;
      --border:#e2e8f0;
      --shadow:0 10px 30px rgba(2,6,23,.08);
    }
    body{font-family:"Space Grotesk",system-ui,-apple-system,Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,#eef2f7 0%,#f8fafc 35%,#ffffff 100%);color:var(--ink);}
    .site-header{position:sticky;top:0;z-index:20;background:rgba(255,255,255,.82);backdrop-filter:saturate(140%) blur(12px);border-bottom:1px solid var(--border);}
    .brand{display:flex;align-items:center;gap:12px;font-weight:700;letter-spacing:.3px;}
    .brand img{height:34px;width:auto;border-radius:8px;}
    .quick-links{display:flex;gap:12px;flex-wrap:wrap;}
    .quick-links a{font-size:.9rem;color:var(--ink);text-decoration:none;padding:6px 12px;border-radius:999px;border:1px solid var(--border);background:var(--card);}
    .quick-links a:hover{border-color:var(--brand);color:var(--brand);}
    .post-shell{background:var(--card);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow);overflow:hidden;}
    .post-hero{padding:24px;border-bottom:1px solid var(--border);}
    .post-cover img{width:100%;max-height:380px;object-fit:cover;display:block;}
    .post-meta{color:var(--muted);font-size:.9rem;display:flex;gap:10px;flex-wrap:wrap;}
    .post-title{font-size:2rem;margin:12px 0 6px;}
    .tags{display:flex;gap:8px;flex-wrap:wrap;}
    .tags a{background:#0f172a;color:#fff;padding:6px 10px;border-radius:999px;text-decoration:none;font-size:.8rem;}
    .post-content{padding:24px;line-height:1.7;}
    .btn{border-radius:999px;padding:8px 16px;font-weight:600;}
    .btn-primary{background:var(--brand);border-color:var(--brand);}
    .btn-primary:hover{background:var(--brand-2);border-color:var(--brand-2);}
    .btn-ghost{border:1px solid var(--border);color:var(--ink);background:#fff;}
    .breadcrumbs a{text-decoration:none;color:var(--muted);}
    .breadcrumbs a:hover{color:var(--brand);}
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
    <nav class="quick-links">
      <a href="{{ route('blog.index') }}">Home</a>
      <a href="{{ route('blog.rss') }}">RSS</a>
      <a href="{{ route('blog.sitemap') }}">Sitemap</a>
    </nav>
    <div class="d-flex gap-2">
      <a class="btn btn-ghost btn-sm" href="{{ route('blog.index') }}">Back</a>
    </div>
  </div>
</header>

<main class="container py-4">
  <div class="breadcrumbs mb-3">
    <a href="{{ route('blog.index') }}">Home</a>
    @if($post->category)
      <span class="text-muted">/</span>
      <a href="{{ route('blog.category',$post->category->slug) }}">{{ $post->category->name }}</a>
    @endif
  </div>

  <article class="post-shell">
    <div class="post-hero">
      <div class="post-meta">
        @if($post->published_at)
          <span>{{ $post->published_at->format('Y-m-d H:i') }}</span>
        @else
          <span>Draft</span>
        @endif
        <span>•</span>
        <span>{{ $post->author?->name }}</span>
      </div>
      <h1 class="post-title">{{ $post->title }}</h1>
      <div class="tags">
        @foreach($post->tags as $t)
          <a href="{{ route('blog.tag',$t->slug) }}">#{{ $t->name }}</a>
        @endforeach
      </div>
    </div>

    @if($post->cover_image)
      <div class="post-cover">
        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" loading="lazy">
      </div>
    @endif

    <div class="post-content">
      {!! $post->body !!}
    </div>
  </article>
</main>
</body>
</html>
