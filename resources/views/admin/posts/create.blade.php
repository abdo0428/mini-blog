@extends('admin.layout')
@section('title','New Post')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" value="{{ old('title') }}" required>
        @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Excerpt</label>
        <input name="excerpt" class="form-control" value="{{ old('excerpt') }}">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            <option value="">None</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="draft" @selected(old('status')=='draft')>Draft</option>
            <option value="published" @selected(old('status')=='published')>Published</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Tags</label>
        <select name="tags[]" class="form-select" multiple>
          @foreach($tags as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Cover Image</label>
        <input type="file" name="cover_image" class="form-control" accept="image/*">
        @error('cover_image') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <div id="bodyEditor" class="border rounded" style="min-height:240px;"></div>
        <textarea id="bodyInput" name="body" class="d-none" required>{{ old('body') }}</textarea>
        @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
  const quill = new Quill('#bodyEditor', {
    theme: 'snow',
    modules: {
      toolbar: [['bold','italic','underline'], [{'list':'ordered'},{'list':'bullet'}], ['link']]
    }
  });

  const bodyInput = document.getElementById('bodyInput');
  if (bodyInput.value) {
    quill.root.innerHTML = bodyInput.value;
  }

  const syncBody = () => {
    bodyInput.value = quill.root.innerHTML;
  };

  quill.on('text-change', syncBody);
  document.querySelector('form').addEventListener('submit', syncBody);
</script>
@endpush
