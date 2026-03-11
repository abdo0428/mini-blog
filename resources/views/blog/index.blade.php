<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $siteName }} - {{ $contextLabel }}</title>
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
    .hero{background:linear-gradient(135deg,#0f172a 0%,#0f766e 50%,#0ea5a4 100%);color:#fff;border-radius:24px;padding:28px;box-shadow:var(--shadow);}
    .hero small{color:rgba(255,255,255,.7);}
    .filter-card{background:var(--card);border:1px solid var(--border);border-radius:18px;padding:18px;box-shadow:var(--shadow);margin-top:-24px;}
    .filter-card .form-control,.filter-card .form-select{border-radius:12px;}
    .posts-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:18px;margin-top:18px;}
    .post-card{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden;box-shadow:var(--shadow);display:flex;flex-direction:column;}
    .post-media img{width:100%;height:180px;object-fit:cover;display:block;}
    .post-placeholder{height:180px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e2e8f0,#f8fafc);color:var(--muted);font-size:.9rem;}
    .post-body{padding:16px;display:flex;flex-direction:column;gap:10px;flex:1;}
    .post-meta{font-size:.85rem;color:var(--muted);display:flex;gap:8px;align-items:center;}
    .post-title{font-size:1.05rem;margin:0;}
    .post-excerpt{color:var(--muted);font-size:.95rem;line-height:1.4;}
    .post-actions{display:flex;gap:10px;margin-top:auto;}
    .btn{border-radius:999px;padding:8px 16px;font-weight:600;}
    .btn-primary{background:var(--brand);border-color:var(--brand);}
    .btn-primary:hover{background:var(--brand-2);border-color:var(--brand-2);}
    .btn-ghost{border:1px solid var(--border);color:var(--ink);background:#fff;}
    .btn-ghost:hover{border-color:var(--brand);color:var(--brand);}
    .btn-outline{border:1px solid var(--border);color:var(--ink);background:#fff;border-radius:999px;padding:8px 16px;}
    .breadcrumb{margin:16px 0;color:var(--muted);}
    .breadcrumb a{text-decoration:none;color:var(--muted);}
    .breadcrumb a:hover{color:var(--brand);}
    .empty-state{border:1px dashed var(--border);border-radius:20px;padding:40px;text-align:center;background:#fff;}
    .empty-icon{font-weight:700;color:var(--muted);margin-bottom:6px;}
    .pagination-wrap{margin:20px 0;}
    #postsWrap.is-loading{opacity:.5;pointer-events:none;}
    .meta-row{display:flex;gap:16px;flex-wrap:wrap;margin-top:8px;}
    .meta-chip{background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.25);padding:6px 12px;border-radius:999px;font-size:.85rem;}
    @media (max-width: 768px){
      .hero{padding:20px;}
      .filter-card{margin-top:0;}
    }
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
    </nav>
    <div class="d-flex gap-2">
      @auth
        <a class="btn btn-dark btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
      @else
        <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Login</a>
      @endauth
    </div>
  </div>
</header>

<main class="container py-4">
  <section class="hero">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
      <div>
        <h2 class="mb-1">Explore the latest ideas</h2>
        <small>Curated posts, smarter filters, and instant search.</small>
        <div class="meta-row">
          <span class="meta-chip" id="resultsCount">{{ $posts->total() }} posts</span>
          <span class="meta-chip" id="resultsContext">{{ $contextLabel }}</span>
        </div>
      </div>
      <div class="text-end">
        <a class="btn btn-light" href="#filters">Quick Filters</a>
      </div>
    </div>
  </section>

  <section id="filters" class="filter-card">
    <form id="filterForm" class="row g-2 align-items-center" method="GET" action="{{ route('blog.index') }}">
      <div class="col-lg-5">
        <input name="q" class="form-control" placeholder="Search by title, excerpt, or content" value="{{ request('q') }}">
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          @foreach($categories as $c)
            <option value="{{ $c->slug }}" @selected(request('category')==$c->slug)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select name="tag" class="form-select">
          <option value="">All Tags</option>
          @foreach($tags as $t)
            <option value="{{ $t->slug }}" @selected(request('tag')==$t->slug)>{{ $t->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1 d-grid">
        <button class="btn btn-primary">Go</button>
      </div>
      <div class="col-md-12">
        <a class="btn btn-ghost btn-sm" href="{{ route('blog.index') }}">Clear all filters</a>
      </div>
    </form>
  </section>

  <nav id="resultsBreadcrumb" class="breadcrumb" aria-label="breadcrumb">
    @include('blog.partials.breadcrumb', ['activeCategory'=>$activeCategory,'activeTag'=>$activeTag,'search'=>$search])
  </nav>

  <div id="postsWrap">
    @include('blog.partials.posts', ['posts' => $posts])
  </div>
</main>

<script>
  const form = document.getElementById('filterForm');
  const postsWrap = document.getElementById('postsWrap');
  const resultsCount = document.getElementById('resultsCount');
  const resultsContext = document.getElementById('resultsContext');
  const resultsBreadcrumb = document.getElementById('resultsBreadcrumb');
  const searchInput = form.querySelector('input[name="q"]');
  let typingTimer = null;

  const toQueryString = (formEl) => {
    const data = new FormData(formEl);
    const params = new URLSearchParams();
    for (const [key, value] of data.entries()) {
      if (value && value.trim() !== '') params.append(key, value);
    }
    return params.toString();
  };

  const fetchResults = (url, pushState = true) => {
    postsWrap.classList.add('is-loading');
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(r => r.json())
      .then(data => {
        postsWrap.innerHTML = data.html;
        resultsCount.textContent = data.count + ' posts';
        resultsContext.textContent = data.context;
        resultsBreadcrumb.innerHTML = data.breadcrumb;
        document.title = data.title;
        if (pushState) history.pushState({}, '', url);
      })
      .catch(() => {
        postsWrap.innerHTML = '<div class="empty-state"><div class="empty-title">Something went wrong</div><div class="empty-text">Please try again.</div></div>';
      })
      .finally(() => postsWrap.classList.remove('is-loading'));
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const qs = toQueryString(form);
    const url = qs ? `{{ route('blog.index') }}?${qs}` : `{{ route('blog.index') }}`;
    fetchResults(url);
  });

  form.querySelectorAll('select').forEach((sel) => {
    sel.addEventListener('change', () => form.dispatchEvent(new Event('submit', { cancelable: true })));
  });

  searchInput.addEventListener('input', () => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => form.dispatchEvent(new Event('submit', { cancelable: true })), 350);
  });

  document.body.addEventListener('click', (e) => {
    const link = e.target.closest('#postsWrap .pagination a');
    if (!link) return;
    e.preventDefault();
    fetchResults(link.href);
  });

  window.addEventListener('popstate', () => {
    const url = window.location.href;
    fetchResults(url, false);
  });
</script>
</body>
</html>
