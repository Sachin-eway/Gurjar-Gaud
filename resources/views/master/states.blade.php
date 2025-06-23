@extends('layouts.main')
@section('title', 'State Master')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

<!-- Table -->
<div class="card">
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-basic table table-bordered state_table">
      <thead>
        <tr>
          <th></th>
          <th>S.No.</th>
          <th>State</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<!--/ Table -->

<!-- Add Modal -->
<div class="offcanvas offcanvas-end" id="add-new-record">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">New State</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body flex-grow-1">
    <form id="stateForm" action="{{ route('add-state') }}" method="POST" class="row g-3">
      @csrf
      <div class="form-floating form-floating-outline">
        <input class="form-control" type="text" name="name" id="name" placeholder="State Name" required />
        <label for="name">State Name</label>
      </div>

      <div class="col-sm-12">
        <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!--/ Add Modal -->

<!-- Edit Modal -->
<div class="offcanvas offcanvas-end" id="stateEdit">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">Edit State</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body flex-grow-1">
    <form id="stateEditForm" action="{{ route('update-state') }}" method="POST" class="row g-3">
      @csrf
      @method('put')

      <div class="form-floating form-floating-outline">
        <input class="form-control" type="text" name="name" id="edit_name" placeholder="State Name" required />
        <label for="edit_name">State Name</label>
      </div>

      <input type="hidden" name="id" id="edit_id">

      <div class="col-sm-12">
        <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </div>
    </form>
  </div>
</div>
<!--/ Edit Modal -->

</div>
@endsection

@section('scripts')
<script>
  var changeStatusURl = "{{ route('change-state-status') }}";
  var deleteUrl = "{{ route('delete-state') }}";
  var stateUrl = "{{ route('states') }}";
</script>
<script src="{{ asset('assets/custom-js/tables-datatables-state.js') }}"></script>
<script src="{{ asset('assets/custom-js/common.js') }}"></script>
@endsection
