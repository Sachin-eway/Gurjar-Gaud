@extends('layouts.main')
@section('title', 'Census Forms')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Census Forms Table -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>S.No.</th>
            <th>Family UID</th>
            <th>Head Name</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Total Members</th>
            <th>Father/Husband Name</th>
            <th>Caste</th>
            <th>Family Deity</th>
            <th>Bank Account</th>
            <th>Whatsapp</th>
            <th>Identity Proof</th>
            <th>Current Address</th>
            <th>Permanent Address</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Edit Offcanvas Modal -->
  <div class="offcanvas offcanvas-end" id="censusEdit">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title">Edit Census Form</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form id="censusEditForm" method="POST" class="row g-3">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="edit_id" />

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="head_name" id="edit_head_name" placeholder="Head Name" required />
          <label for="edit_head_name">Head Name</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select class="form-select" name="gender" id="edit_gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
          <label for="edit_gender">Gender</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="date" name="dob" id="edit_dob" />
          <label for="edit_dob">Date of Birth</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="contact_number" id="edit_contact" />
          <label for="edit_contact">Contact Number</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="email" name="email" id="edit_email" />
          <label for="edit_email">Email</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="family_uid" id="edit_family_uid" />
          <label for="edit_family_uid">Family UID</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="father_or_husband_name" id="edit_father_or_husband_name" />
          <label for="edit_father_or_husband_name">Father/Husband Name</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="caste" id="edit_caste" />
          <label for="edit_caste">Caste</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="family_deity" id="edit_family_deity" />
          <label for="edit_family_deity">Family Deity</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="bank_account" id="edit_bank_account" />
          <label for="edit_bank_account">Bank Account</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="whatsapp" id="edit_whatsapp" />
          <label for="edit_whatsapp">Whatsapp</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="identity_proof" id="edit_identity_proof" />
          <label for="edit_identity_proof">Identity Proof</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="current_address" id="edit_current_address" />
          <label for="edit_current_address">Current Address</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="permanent_address" id="edit_permanent_address" />
          <label for="edit_permanent_address">Permanent Address</label>
        </div>

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="number" name="total_members" id="edit_total_members" />
          <label for="edit_total_members">Total Members</label>
        </div>

        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Are you sure you want to delete this record?</div>
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
  var deleteUrl = "{{ url('delete-census-form') }}";
  var updateUrl = "{{ url('update-census-form') }}";
  var dataUrl   = "{{ url('census-forms') }}";
</script>
<script src="{{ asset('assets/custom-js/tables-datatables-census.js') }}"></script>
<script src="{{ asset('assets/custom-js/common.js') }}"></script>
@endsection
