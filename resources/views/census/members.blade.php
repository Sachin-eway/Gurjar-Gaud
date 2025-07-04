@extends('layouts.main')
@section('title', 'Census Members')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Census Members Table -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-members table table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>S.No.</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Marital Status</th>
            <th>Education</th>
            <th>Occupation</th>
            <th>Income Source</th>
            <th>Mobile</th>
            <th>WhatsApp</th>
            <th>Identity Proof</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Edit Offcanvas -->
  <div class="offcanvas offcanvas-end" id="memberEdit">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title">Edit Member</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form id="memberEditForm" method="POST" class="row g-3">
        @csrf
        @method('PUT')
        <input type="hidden" name="census_form_id" id="edit_census_form_id" />

        <div class="form-floating form-floating-outline">
          <input type="text" name="full_name" id="edit_full_name" class="form-control" />
          <label for="edit_full_name">Full Name</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select name="gender" id="edit_gender" class="form-select">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
          <label for="edit_gender">Gender</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="date" name="dob" id="edit_dob" class="form-control" />
          <label for="edit_dob">DOB</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select name="marital_status" id="edit_marital_status" class="form-select">
            <option value="">Select Marital Status</option>
            <option value="Unmarried">Unmarried</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Divorced">Divorced</option>
            <option value="Separated">Separated</option>
          </select>
          <label for="edit_marital_status">Marital Status</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="education" id="edit_education" class="form-control" />
          <label for="edit_education">Education</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="occupation" id="edit_occupation" class="form-control" />
          <label for="edit_occupation">Occupation</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="income_source" id="edit_income_source" class="form-control" />
          <label for="edit_income_source">Income Source</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="mobile" id="edit_mobile" class="form-control" />
          <label for="edit_mobile">Mobile</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="whatsapp" id="edit_whatsapp" class="form-control" />
          <label for="edit_whatsapp">WhatsApp</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input type="text" name="identity_proof" id="edit_identity_proof" class="form-control" />
          <label for="edit_identity_proof">Identity Proof</label>
        </div>

        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h5>Confirm Deletion</h5></div>
        <div class="modal-body">Are you sure you want to delete this member?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  const deleteUrl = "{{ url('delete-census-member') }}";
  const updateUrl = "{{ url('update-census-member') }}";
  const dataUrl   = "{{ url('census-members') }}";
</script>
<script src="{{ asset('assets/custom-js/tables-datatables-members.js') }}"></script>
@endsection
