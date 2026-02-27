<div class="posts-grid">
  @forelse($posts as $post)
    <article class="post-card">
      <div class="post-media">
        @if($post->cover_image)
          <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" loading="lazy">
        @else
          <div class="post-placeholder">No Image</div>
        @endif
      </div>
      <div class="post-body">
        <div class="post-meta">
          <span>{{ optional($post->published_at)->format('Y-m-d') }}</span>
          <span>•</span>
          <span>{{ $post->category?->name ?? 'Uncategorized' }}</span>
        </div>
        <h3 class="post-title">{{ $post->title }}</h3>
        <p class="post-excerpt">{{ $post->excerpt }}</p>
        <div class="post-actions">
          <a class="btn btn-primary" href="{{ route('blog.show',$post->slug) }}">Read</a>
        </div>
      </div>
    </article>
  @empty
    <div class="empty-state">
      <div class="empty-icon">No Results</div>
      <div class="empty-title">No posts found</div>
      <div class="empty-text">Try a different keyword or clear filters.</div>
      <a class="btn btn-outline" href="{{ route('blog.index') }}">Clear filters</a>
    </div>
  @endforelse
</div>

@if($posts->hasPages())
  <div class="pagination-wrap">
    {{ $posts->links() }}
  </div>
@endif
