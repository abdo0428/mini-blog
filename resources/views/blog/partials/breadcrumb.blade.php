<ol class="breadcrumb mb-0">
  <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Home</a></li>
  @if($activeCategory)
    <li class="breadcrumb-item active">Category: {{ $activeCategory->name }}</li>
  @elseif($activeTag)
    <li class="breadcrumb-item active">Tag: {{ $activeTag->name }}</li>
  @elseif($search)
    <li class="breadcrumb-item active">Search: {{ $search }}</li>
  @else
    <li class="breadcrumb-item active">All Posts</li>
  @endif
</ol>
