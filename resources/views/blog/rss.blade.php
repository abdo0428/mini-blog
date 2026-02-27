<?xml version="1.0" encoding="UTF-8"?>
@php($siteName = \App\Models\Setting::get('site_name', config('app.name')))
<rss version="2.0">
  <channel>
    <title>{{ $siteName }}</title>
    <link>{{ url('/') }}</link>
    <description>Latest posts from {{ $siteName }}</description>
    @foreach($posts as $post)
      <item>
        <title>{{ $post->title }}</title>
        <link>{{ route('blog.show', $post->slug) }}</link>
        <guid>{{ route('blog.show', $post->slug) }}</guid>
        <pubDate>{{ optional($post->published_at)->toRfc2822String() }}</pubDate>
        <description><![CDATA[{!! $post->excerpt !!}]]></description>
      </item>
    @endforeach
  </channel>
</rss>
