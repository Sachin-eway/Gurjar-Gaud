@extends('layouts.main')
@section('title', 'City Master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Table -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table table-bordered city_table">
        <thead>
          <tr>
            <th></th>
            <th>S.No.</th>
            <th>City</th>
            <th>District</th>
            <th>State</th>
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
      <h5 class="offcanvas-title">New City</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form id="cityForm" action="{{ route('add-city') }}" method="POST" class="row g-3">
        @csrf

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="city" id="city" placeholder="City Name" required />
          <label for="city">City Name</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select class="form-select" name="state_id" id="state_id" required>
            <option value="" disabled selected>Select State</option>
            @foreach(\App\Models\State::all() as $state)
              <option value="{{ $state->state_id }}">{{ $state->name }}</option>
            @endforeach
          </select>
          <label for="state_id">State</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select class="form-select" name="district_id" id="district_id" required>
            <option value="" disabled selected>Select District</option>
            @foreach(\App\Models\District::all() as $district)
              <option value="{{ $district->distt_id }}">{{ $district->name }}</option>
            @endforeach
          </select>
          <label for="district_id">District</label>
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
  <div class="offcanvas offcanvas-end" id="cityEdit">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title">Edit City</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form id="cityEditForm" action="{{ route('update-city') }}" method="POST" class="row g-3">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" id="edit_id" />

        <div class="form-floating form-floating-outline">
          <input class="form-control" type="text" name="city" id="edit_city" placeholder="City Name" required />
          <label for="edit_city">City Name</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select class="form-select" name="state_id" id="edit_state_id" required>
            <option value="" disabled selected>Select State</option>
            @foreach(\App\Models\State::all() as $state)
              <option value="{{ $state->state_id }}">{{ $state->name }}</option>
            @endforeach
          </select>
          <label for="edit_state_id">State</label>
        </div>

        <div class="form-floating form-floating-outline">
          <select class="form-select" name="district_id" id="edit_district_id" required>
            <option value="" disabled selected>Select District</option>
            @foreach(\App\Models\District::all() as $district)
              <option value="{{ $district->distt_id }}">{{ $district->name }}</option>
            @endforeach
          </select>
          <label for="edit_district_id">District</label>
        </div>


        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Update</button>
          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <!--/ Edit Modal -->

  <!--/ Delete Modal -->

</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  var deleteUrl = "{{ route('city') }}";
  var fetchUrl = "{{ route('cities') }}";
</script>
<script src="{{ asset('assets/custom-js/tables-datatables-city.js') }}"></script>
<script src="{{ asset('assets/custom-js/common.js') }}"></script>
@endsection
