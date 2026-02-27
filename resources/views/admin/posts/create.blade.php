@extends('admin.layout')
@section('title','مقال جديد')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">العنوان</label>
        <input name="title" class="form-control" value="{{ old('title') }}" required>
        @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">وصف مختصر</label>
        <input name="excerpt" class="form-control" value="{{ old('excerpt') }}">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">التصنيف</label>
          <select name="category_id" class="form-select">
            <option value="">بدون</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">الحالة</label>
          <select name="status" class="form-select" required>
            <option value="draft" @selected(old('status')=='draft')>Draft</option>
            <option value="published" @selected(old('status')=='published')>Published</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">الوسوم (Tags)</label>
        <select name="tags[]" class="form-select" multiple>
          @foreach($tags as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">صورة الغلاف</label>
        <input type="file" name="cover_image" class="form-control" accept="image/*">
        @error('cover_image') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">المحتوى</label>
        <textarea name="body" class="form-control" rows="10" required>{{ old('body') }}</textarea>
        @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection