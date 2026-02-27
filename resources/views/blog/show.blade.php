<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $post->title }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm mb-3">← Back</a>

  <div class="card shadow-sm">
    @if($post->cover_image)
      <img src="{{ asset('storage/'.$post->cover_image) }}" class="card-img-top" style="max-height:360px;object-fit:cover">
    @endif

    <div class="card-body">
      <div class="small text-muted mb-2">
        {{ optional($post->published_at)->format('Y-m-d H:i') }} • {{ $post->author?->name }}
        • <a href="{{ route('blog.category',$post->category?->slug ?? '') }}">{{ $post->category?->name }}</a>
      </div>

      <h2 class="mb-3">{{ $post->title }}</h2>

      <div class="mb-3">
        @foreach($post->tags as $t)
          <a class="badge bg-dark text-decoration-none" href="{{ route('blog.tag',$t->slug) }}">#{{ $t->name }}</a>
        @endforeach
      </div>

      <div class="fs-6" style="white-space:pre-line">{{ $post->body }}</div>
    </div>
  </div>
</div>
</body>
</html>