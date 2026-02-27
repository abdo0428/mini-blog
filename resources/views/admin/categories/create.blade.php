@extends('admin.layout')
@section('title','تصنيف جديد')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">إضافة تصنيف</h5>

    <form method="POST" action="{{ route('admin.categories.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">اسم التصنيف</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">رجوع</a>
      </div>
    </form>
  </div>
</div>
@endsection