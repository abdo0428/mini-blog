@extends('admin.layout')
@section('title','Tags')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Tags</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">+ New Tag</button>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <table id="tagsTable" class="table table-striped table-bordered w-100">
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

<div class="modal fade" id="createTagModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Tag</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="createTagForm">
          <div class="mb-3">
            <label class="form-label">Tag Name</label>
            <input name="name" class="form-control" required>
            <div class="invalid-feedback" id="tagError"></div>
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
  const table = $('#tagsTable').DataTable({
    ajax: "{{ route('admin.tags.data') }}",
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
          <a class="btn btn-outline-primary" href="/admin/tags/${row.id}/edit">Edit</a>
          <button class="btn btn-outline-danger" onclick="deleteTag(${row.id})">Delete</button>
        </div>
      `}
    ]
  });

  window.deleteTag = function(id){
    Swal.fire({
      title: 'Confirm delete?',
      text: 'This will remove the tag from all posts.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel'
    }).then((r)=>{
      if(!r.isConfirmed) return;
      $.ajax({
        url: `/admin/tags/${id}`,
        type: 'DELETE',
        success: function(){
          Swal.fire('Deleted','Tag removed','success');
          table.ajax.reload(null,false);
        },
        error: function(xhr){
          Swal.fire('Error', xhr?.responseJSON?.message ?? 'Unable to delete', 'error');
        }
      })
    });
  }

  $('#createTagForm').on('submit', function(e){
    e.preventDefault();
    const form = $(this);
    const input = form.find('input[name="name"]');
    $('#tagError').text('');
    input.removeClass('is-invalid');

    $.post("{{ route('admin.tags.store') }}", form.serialize())
      .done(function(){
        form[0].reset();
        const modalEl = document.getElementById('createTagModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        table.ajax.reload(null,false);
        Swal.fire({
          toast: true,
          position: 'top-start',
          icon: 'success',
          title: 'Tag created',
          showConfirmButton: false,
          timer: 2000
        });
      })
      .fail(function(xhr){
        const msg = xhr?.responseJSON?.errors?.name?.[0] ?? 'Unable to save';
        $('#tagError').text(msg);
        input.addClass('is-invalid');
      });
  });
});
</script>
@endpush
