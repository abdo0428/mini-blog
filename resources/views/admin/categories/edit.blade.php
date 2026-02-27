@extends('admin.layout')
@section('title','Edit Category')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Edit Category</h5>

    <form method="POST" action="{{ route('admin.categories.update',$category) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input name="name" class="form-control" value="{{ old('name',$category->name) }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="alert alert-light border small">
        <div><strong>Slug:</strong> {{ $category->slug }}</div>
        <div class="text-muted">Slug updates automatically if the name changes.</div>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
