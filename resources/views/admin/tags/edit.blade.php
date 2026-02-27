@extends('admin.layout')
@section('title','Edit Tag')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Edit Tag</h5>

    <form method="POST" action="{{ route('admin.tags.update',$tag) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Tag Name</label>
        <input name="name" class="form-control" value="{{ old('name',$tag->name) }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="alert alert-light border small">
        <div><strong>Slug:</strong> {{ $tag->slug }}</div>
        <div class="text-muted">Slug updates automatically if the name changes.</div>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
