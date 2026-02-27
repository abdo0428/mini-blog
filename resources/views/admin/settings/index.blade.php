@extends('admin.layout')
@section('title','Site Settings')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Site Name</label>
        <input name="site_name" class="form-control" value="{{ old('site_name',$siteName) }}" required>
        @error('site_name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label d-block">Current Logo</label>
        @if($siteLogo)
          <img src="{{ asset('storage/'.$siteLogo) }}" class="rounded border mb-2" style="max-width:200px">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogo">
            <label class="form-check-label" for="removeLogo">Remove logo</label>
        </div>
      @else
          <div class="text-muted small">No logo uploaded</div>
      @endif
      </div>

      <div class="mb-3">
        <label class="form-label">Change Logo</label>
        <input type="file" name="site_logo" class="form-control" accept="image/*">
        @error('site_logo') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-success">Save</button>
    </form>
  </div>
</div>
@endsection
