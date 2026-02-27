@extends('admin.layout')
@section('title','Edit Post')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.posts.update',$post) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" value="{{ old('title',$post->title) }}" required>
        @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Slug</label>
        <div class="input-group">
          <input id="slugInput" name="slug" class="form-control" value="{{ old('slug',$post->slug) }}" readonly>
          <button class="btn btn-outline-secondary" type="button" id="editSlugBtn">Edit</button>
        </div>
        <div class="form-text">Leave it as-is unless you need to change it.</div>
        @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Excerpt</label>
        <input name="excerpt" class="form-control" value="{{ old('excerpt',$post->excerpt) }}">
        @error('excerpt') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            <option value="">None</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(old('category_id',$post->category_id)==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" required>
            <option value="draft" @selected(old('status',$post->status)=='draft')>Draft</option>
            <option value="published" @selected(old('status',$post->status)=='published')>Published</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Tags</label>
        <select name="tags[]" class="form-select" multiple>
          @foreach($tags as $t)
            <option value="{{ $t->id }}" @selected(in_array($t->id, old('tags',$selectedTags)))>{{ $t->name }}</option>
          @endforeach
        </select>
      </div>

      @if($post->cover_image)
        <div class="mb-3">
          <label class="form-label d-block">Current Cover</label>
          <img src="{{ asset('storage/'.$post->cover_image) }}" class="rounded border" style="max-width:220px" loading="lazy">
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_cover" value="1" id="removeCover">
            <label class="form-check-label" for="removeCover">Remove cover</label>
          </div>
        </div>
      @endif

      <div class="mb-3">
        <label class="form-label">Change Cover Image</label>
        <input type="file" name="cover_image" class="form-control" accept="image/*">
        @error('cover_image') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <div id="bodyEditor" class="border rounded" style="min-height:240px;"></div>
        <textarea id="bodyInput" name="body" class="d-none" required>{{ old('body',$post->body) }}</textarea>
        @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div id="autosaveStatus" class="small text-muted mb-3"></div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">Update</button>
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
  let dirty = false;

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

  quill.on('text-change', () => {
    dirty = true;
    syncBody();
  });

  const slugInput = document.getElementById('slugInput');
  const editSlugBtn = document.getElementById('editSlugBtn');
  editSlugBtn.addEventListener('click', () => {
    slugInput.removeAttribute('readonly');
    slugInput.focus();
  });

  const form = document.querySelector('form');
  form.addEventListener('input', () => { dirty = true; });
  form.addEventListener('submit', syncBody);

  const autosaveUrl = "{{ route('admin.posts.autosave', $post) }}";
  const statusEl = document.getElementById('autosaveStatus');

  setInterval(() => {
    if (!dirty) return;
    const statusField = document.querySelector('select[name="status"]');
    if (statusField && statusField.value !== 'draft') return;
    syncBody();
    $.ajax({
      url: autosaveUrl,
      type: 'PATCH',
      data: $(form).serialize(),
      success: function(res){
        dirty = false;
        statusEl.textContent = `Last autosave: ${res.saved_at}`;
      }
    });
  }, 25000);
</script>
@endpush
