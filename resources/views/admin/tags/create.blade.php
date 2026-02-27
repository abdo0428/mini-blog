@extends('admin.layout')
@section('title','New Tag')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Add Tag</h5>

    <form method="POST" action="{{ route('admin.tags.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Tag Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection
