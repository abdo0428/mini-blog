@extends('admin.layout')
@section('title','المقالات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">المقالات</h4>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ مقال جديد</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table id="postsTable" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>العنوان</th>
          <th>التصنيف</th>
          <th>الحالة</th>
          <th>الكاتب</th>
          <th>نشر</th>
          <th>إنشاء</th>
          <th width="120">إجراءات</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  const table = $('#postsTable').DataTable({
    ajax: "{{ route('admin.posts.data') }}",
    columns: [
      {data:'id'},
      {data:'title'},
      {data:'category'},
      {data:'status', render: (v)=> v === 'published'
        ? '<span class="badge bg-success">Published</span>'
        : '<span class="badge bg-secondary">Draft</span>'
      },
      {data:'author'},
      {data:'published_at'},
      {data:'created_at'},
      {data:null, orderable:false, searchable:false, render: (row)=> `
        <div class="btn-group btn-group-sm">
          <a class="btn btn-outline-primary" href="/admin/posts/${row.id}/edit">Edit</a>
          <button class="btn btn-outline-danger" onclick="deletePost(${row.id})">Del</button>
        </div>
      `}
    ]
  });

  window.deletePost = function(id){
    Swal.fire({
      title: 'تأكيد الحذف؟',
      text: 'لن يمكنك استرجاعه بعد الحذف',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'نعم احذف',
      cancelButtonText: 'إلغاء'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/posts/${id}`,
        type: 'DELETE',
        success: function(){
          Swal.fire('تم','تم حذف المقال','success');
          table.ajax.reload(null,false);
        },
        error: function(){
          Swal.fire('خطأ','تعذر الحذف','error');
        }
      })
    });
  }
});
</script>
@endpush