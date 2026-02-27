@extends('admin.layout')
@section('title','Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Categories</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">+ New Category</button>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table id="catsTable" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Created At</th>
          <th width="140">Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="createCategoryForm">
          <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input name="name" class="form-control" required>
            <div class="invalid-feedback" id="catError"></div>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  const table = $('#catsTable').DataTable({
    ajax: "{{ route('admin.categories.data') }}",
    processing: true,
    language: {
      emptyTable: 'No data available',
      processing: 'Loading...'
    },
    columns: [
      {data:'id'},
      {data:'name'},
      {data:'slug'},
      {data:'created_at'},
      {data:null, orderable:false, searchable:false, render: (row)=> `
        <div class="btn-group btn-group-sm">
          <a class="btn btn-outline-primary" href="/admin/categories/${row.id}/edit">Edit</a>
          <button class="btn btn-outline-danger" onclick="deleteCat(${row.id})">Delete</button>
        </div>
      `}
    ]
  });

  window.deleteCat = function(id){
    Swal.fire({
      title: 'Confirm delete?',
      text: 'Posts will not be deleted, but may become uncategorized.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/categories/${id}`,
        type: 'DELETE',
        success: function(){
          Swal.fire('Deleted','Category removed','success');
          table.ajax.reload(null,false);
        },
        error: function(xhr){
          Swal.fire('Error', xhr?.responseJSON?.message ?? 'Unable to delete', 'error');
        }
      })
    });
  }

  $('#createCategoryForm').on('submit', function(e){
    e.preventDefault();
    const form = $(this);
    const input = form.find('input[name="name"]');
    $('#catError').text('');
    input.removeClass('is-invalid');

    $.post("{{ route('admin.categories.store') }}", form.serialize())
      .done(function(){
        form[0].reset();
        const modalEl = document.getElementById('createCategoryModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        table.ajax.reload(null,false);
        Swal.fire({
          toast: true,
          position: 'top-start',
          icon: 'success',
          title: 'Category created',
          showConfirmButton: false,
          timer: 2000
        });
      })
      .fail(function(xhr){
        const msg = xhr?.responseJSON?.errors?.name?.[0] ?? 'Unable to save';
        $('#catError').text(msg);
        input.addClass('is-invalid');
      });
  });
});
</script>
@endpush
