@extends('admin.layout')
@section('title','Dashboard')

@section('content')
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <a class="card shadow-sm text-decoration-none" href="{{ route('admin.posts.index') }}">
      <div class="card-body">
        <div class="text-muted small">Posts</div>
        <div class="fw-bold">View All</div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a class="card shadow-sm text-decoration-none" href="{{ route('admin.posts.create') }}">
      <div class="card-body">
        <div class="text-muted small">Quick Action</div>
        <div class="fw-bold">New Post</div>
      </div>
    </a>
  </div>
  @role('admin')
    <div class="col-md-3">
      <a class="card shadow-sm text-decoration-none" href="{{ route('admin.categories.index') }}">
        <div class="card-body">
          <div class="text-muted small">Categories</div>
          <div class="fw-bold">Manage Categories</div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a class="card shadow-sm text-decoration-none" href="{{ route('admin.tags.index') }}">
        <div class="card-body">
          <div class="text-muted small">Tags</div>
          <div class="fw-bold">Manage Tags</div>
        </div>
      </a>
    </div>
  @endrole
</div>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Published Posts</div>
        <div class="fs-3 fw-bold">{{ $publishedCount }}</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Drafts</div>
        <div class="fs-3 fw-bold">{{ $draftCount }}</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Categories</div>
        <div class="fs-3 fw-bold">{{ $categoryCount }}</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Tags</div>
        <div class="fs-3 fw-bold">{{ $tagCount }}</div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Latest 5 Posts</h5>
    <div class="table-responsive">
      <table class="table table-striped table-bordered mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Author</th>
            <th>Published At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($latestPosts as $post)
            <tr>
              <td>{{ $post->title }}</td>
              <td>{{ $post->category?->name ?? '-' }}</td>
              <td>
                @if($post->status === 'published')
                  <span class="badge bg-success">Published</span>
                @else
                  <span class="badge bg-secondary">Draft</span>
                @endif
              </td>
              <td>{{ $post->author?->name ?? '-' }}</td>
              <td>{{ optional($post->published_at)->format('Y-m-d H:i') ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted">No posts yet</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
