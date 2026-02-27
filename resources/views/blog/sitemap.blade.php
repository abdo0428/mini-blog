<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{{ url('/') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
  </url>
  @foreach($posts as $post)
    <url>
      <loc>{{ route('blog.show', $post->slug) }}</loc>
      <lastmod>{{ optional($post->updated_at ?? $post->published_at)->toAtomString() }}</lastmod>
    </url>
  @endforeach
</urlset>
