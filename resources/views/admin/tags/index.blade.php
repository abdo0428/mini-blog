@extends('admin.layout')
@section('title','الوسوم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الوسوم (Tags)</h4>
  <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">+ وسم جديد</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table id="tagsTable" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>الاسم</th>
          <th>Slug</th>
          <th>تاريخ الإنشاء</th>
          <th width="140">إجراءات</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  const table = $('#tagsTable').DataTable({
    ajax: "{{ route('admin.tags.data') }}",
    columns: [
      {data:'id'},
      {data:'name'},
      {data:'slug'},
      {data:'created_at'},
      {data:null, orderable:false, searchable:false, render: (row)=> `
        <div class="btn-group btn-group-sm">
          <a class="btn btn-outline-primary" href="/admin/tags/${row.id}/edit">Edit</a>
          <button class="btn btn-outline-danger" onclick="deleteTag(${row.id})">Del</button>
        </div>
      `}
    ]
  });

  window.deleteTag = function(id){
    Swal.fire({
      title: 'تأكيد الحذف؟',
      text: 'سيتم حذف الوسم وفك ارتباطه من المقالات.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'نعم احذف',
      cancelButtonText: 'إلغاء'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/tags/${id}`,
        type: 'DELETE',
        success: function(){
          Swal.fire('تم','تم حذف الوسم','success');
          table.ajax.reload(null,false);
        },
        error: function(xhr){
          Swal.fire('خطأ', xhr?.responseJSON?.message ?? 'تعذر الحذف', 'error');
        }
      })
    });
  }
});
</script>
@endpush