@extends('admin.layout')
@section('title','تعديل المقال')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.posts.update',$post) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">العنوان</label>
        <input name="title" class="form-control" value="{{ old('title',$post->title) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">وصف مختصر</label>
        <input name="excerpt" class="form-control" value="{{ old('excerpt',$post->excerpt) }}">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">التصنيف</label>
          <select name="category_id" class="form-select">
            <option value="">بدون</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(old('category_id',$post->category_id)==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">الحالة</label>
          <select name="status" class="form-select" required>
            <option value="draft" @selected(old('status',$post->status)=='draft')>Draft</option>
            <option value="published" @selected(old('status',$post->status)=='published')>Published</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">الوسوم</label>
        <select name="tags[]" class="form-select" multiple>
          @foreach($tags as $t)
            <option value="{{ $t->id }}" @selected(in_array($t->id, old('tags',$selectedTags)))>{{ $t->name }}</option>
          @endforeach
        </select>
      </div>

      @if($post->cover_image)
        <div class="mb-3">
          <label class="form-label d-block">الغلاف الحالي</label>
          <img src="{{ asset('storage/'.$post->cover_image) }}" class="rounded border" style="max-width:220px">
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="remove_cover" value="1" id="removeCover">
            <label class="form-check-label" for="removeCover">إزالة الغلاف</label>
          </div>
        </div>
      @endif

      <div class="mb-3">
        <label class="form-label">تغيير صورة الغلاف</label>
        <input type="file" name="cover_image" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label">المحتوى</label>
        <textarea name="body" class="form-control" rows="10" required>{{ old('body',$post->body) }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">تحديث</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection