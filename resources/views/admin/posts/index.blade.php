@extends('admin.layout')
@section('title','Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Posts</h4>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Post</a>
</div>

<div class="row g-2 mb-3">
  <div class="col-md-3">
    <select id="statusFilter" class="form-select">
      <option value="">All Statuses</option>
      <option value="published">Published</option>
      <option value="draft">Draft</option>
    </select>
  </div>
  <div class="col-md-4">
    <select id="categoryFilter" class="form-select">
      <option value="">All Categories</option>
      @foreach($categories as $c)
        <option value="{{ $c->name }}">{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-5 d-flex gap-2">
    <input id="quickSearch" type="text" class="form-control" placeholder="Quick search...">
    <button id="clearFilters" type="button" class="btn btn-outline-secondary">Clear</button>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table id="postsTable" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Status</th>
          <th>Author</th>
          <th>Published</th>
          <th>Created</th>
          <th width="220">Actions</th>
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
    processing: true,
    language: {
      emptyTable: 'No data available',
      processing: 'Loading...'
    },
    columns: [
      {data:'id'},
      {data:'title'},
      {data:'category'},
      {data:'status', render: (v, type)=> {
        if (type !== 'display') return v;
        return v === 'published'
          ? '<span class="badge bg-success">Published</span>'
          : '<span class="badge bg-secondary">Draft</span>';
      }},
      {data:'author'},
      {data:'published_at'},
      {data:'created_at'},
      {data:null, orderable:false, searchable:false, render: (row)=> `
        <div class="btn-group btn-group-sm flex-wrap">
          <a class="btn btn-outline-primary" href="/admin/posts/${row.id}/edit">Edit</a>
          <button class="btn btn-outline-secondary" onclick="previewPost(${row.id})">Preview</button>
          <button class="btn ${row.status === 'published' ? 'btn-warning' : 'btn-success'}"
            onclick="togglePost(${row.id})">
            ${row.status === 'published' ? 'Unpublish' : 'Publish'}
          </button>
          <button class="btn btn-outline-dark" onclick="duplicatePost(${row.id})">Duplicate</button>
          <button class="btn btn-outline-danger" onclick="deletePost(${row.id})">Delete</button>
        </div>
      `}
    ]
  });

  $('#statusFilter').on('change', function () {
    table.column(3).search(this.value, false, true).draw();
  });

  $('#categoryFilter').on('change', function () {
    table.column(2).search(this.value, false, true).draw();
  });

  $('#quickSearch').on('keyup', function () {
    table.search(this.value).draw();
  });

  $('#clearFilters').on('click', function () {
    $('#statusFilter').val('');
    $('#categoryFilter').val('');
    $('#quickSearch').val('');
    table.search('').columns().search('').draw();
  });

  window.deletePost = function(id){
    Swal.fire({
      title: 'Confirm delete?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/posts/${id}`,
        type: 'DELETE',
        success: function(){
          Swal.fire('Deleted','Post has been deleted','success');
          table.ajax.reload(null,false);
        },
        error: function(){
          Swal.fire('Error','Unable to delete','error');
        }
      })
    });
  }

  window.togglePost = function(id){
    Swal.fire({
      title: 'Change status?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'Cancel'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/posts/${id}/toggle`,
        type: 'PATCH',
        success: function(){
          table.ajax.reload(null,false);
        },
        error: function(){
          Swal.fire('Error','Unable to change status','error');
        }
      })
    });
  }

  window.duplicatePost = function(id){
    Swal.fire({
      title: 'Duplicate post?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'Cancel'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/posts/${id}/duplicate`,
        type: 'POST',
        success: function(){
          table.ajax.reload(null,false);
        },
        error: function(){
          Swal.fire('Error','Unable to duplicate','error');
        }
      })
    });
  }

  window.previewPost = function(id){
    window.open(`/admin/posts/${id}/preview`, '_blank');
  }
});
</script>
@endpush
