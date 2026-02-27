<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mini Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Mini Blog</h3>
    <div class="d-flex gap-2">
      @auth
        <a class="btn btn-dark btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
      @else
        <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Login</a>
      @endauth
    </div>
  </div>

  <div class="row g-3">
    @foreach($posts as $post)
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          @if($post->cover_image)
            <img src="{{ asset('storage/'.$post->cover_image) }}" class="card-img-top" style="height:180px;object-fit:cover">
          @endif
          <div class="card-body">
            <div class="small text-muted mb-1">
              {{ optional($post->published_at)->format('Y-m-d') }}
              • {{ $post->category?->name ?? 'No Category' }}
            </div>
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">{{ $post->excerpt }}</p>
            <a href="{{ route('blog.show',$post->slug) }}" class="btn btn-primary btn-sm">Read</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">
    {{ $posts->links() }}
  </div>
</div>
</body>
</html>