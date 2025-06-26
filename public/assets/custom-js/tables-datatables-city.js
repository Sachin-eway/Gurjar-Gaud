"use strict";

let cityCanvasEl, cityFv, deleteCityId;

$(document).ready(function () {
  const formAddNewCity = document.getElementById("cityForm");
  const formEditCity = document.getElementById("cityEditForm");

  // Open Add Modal
  setTimeout(() => {
    const newRecord = document.querySelector(".create-new"),
      canvasEl = document.querySelector("#add-new-record");

    if (newRecord) {
      newRecord.addEventListener("click", function () {
        cityCanvasEl = new bootstrap.Offcanvas(canvasEl);
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").empty();
        $("#cityForm").trigger("reset");
        if (typeof cityFv !== "undefined") {
          cityFv.resetForm(true);
        }
        cityCanvasEl.show();
      });
    }
  }, 200);

  // FormValidation Init for Add
  if (formAddNewCity) {
    cityFv = FormValidation.formValidation(formAddNewCity, {
      fields: {
        city: { validators: { notEmpty: { message: "Please enter city name" } } },
        state_id: { validators: { notEmpty: { message: "Please select a state" } } },
        district_id: { validators: { notEmpty: { message: "Please select a district" } } },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: "",
          rowSelector: ".form-floating",
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus(),
      },
    }).on("core.form.valid", function () {
      $.post(formAddNewCity.action, $(formAddNewCity).serialize(), function () {
        $(".offcanvas").offcanvas("hide");
        $(".datatables-basic").DataTable().ajax.reload();
        toastr.success("City added successfully");
      }).fail(function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Add failed");
      });
    });
  }

  // DataTable Init
  const dt_table = $(".datatables-basic");
  let dt;

  if (dt_table.length) {
    dt = dt_table.DataTable({
      ajax: {
        url: fetchUrl,
        dataSrc: function (json) {
          json.data.forEach((row, index) => {
            row.sequence_number = index + 1;
            row.canEdit = json.canEdit || false;
            row.canDelete = json.canDelete || false;
          });

          if (json.canAdd) $(".create-new").removeClass("d-none");
          return json.data;
        },
      },
      columns: [
        { data: null },
        { data: "sequence_number" },
        { data: "city" },
        { data: "district_name", defaultContent: "" },
        { data: "state_name", defaultContent: "" },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            const editBtn = row.canEdit
              ? `<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon edit-record" 
                  data-id="${row.id}" data-name="${row.city}" data-state="${row.state_id}" data-district="${row.district_id}">
                  <i class="mdi mdi-pencil-outline"></i></a>`
              : "";
            const deleteBtn = row.canDelete
              ? `<a href="javascript:;" class="btn btn-sm btn-icon text-danger delete-record" data-id="${row.id}" 
                  data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                  <i class="mdi mdi-delete"></i></a>`
              : "";
            return editBtn || deleteBtn ? editBtn + deleteBtn : "Permission Denied";
          },
        },
      ],
      columnDefs: [
        {
          targets: 0,
          className: "text-center",
          orderable: false,
          render: () => '<i class="mdi mdi-drag"></i>',
        },
      ],
      dom:
        '<"card-header d-flex justify-content-between align-items-center flex-wrap"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>>' +
        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>' +
        "t" +
        '<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 10,
      lengthMenu: [10, 25, 50, 100],
      buttons: [
        {
          extend: "collection",
          className: "btn btn-label-primary dropdown-toggle me-2 waves-effect",
          text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
          buttons: [
            { extend: "csv", className: "dropdown-item", text: "CSV" },
            { extend: "excel", className: "dropdown-item", text: "Excel" },
            { extend: "copy", className: "dropdown-item", text: "Copy" },
          ],
        },
        {
          text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
          className: "create-new btn btn-primary d-none",
        },
      ],
    });

    $("div.head-label").html('<h5 class="card-title mb-0">Cities</h5>');
  }

  // Edit button click
  $(document).on("click", ".edit-record", function () {
    const id = $(this).data("id");
    const city = $(this).data("name");
    const stateId = $(this).data("state");
    const districtId = $(this).data("district");

    $("#edit_id").val(id);
    $("#edit_city").val(city);
    $("#edit_state_id").val(stateId);
    $("#edit_district_id").val(districtId);

    const editCanvas = new bootstrap.Offcanvas(document.getElementById("cityEdit"));
    editCanvas.show();
  });

  // Edit Submit
  $("#cityEditForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: this.action,
      method: "POST",
      data: $(this).serialize(),
      success: function () {
        $(".offcanvas").offcanvas("hide");
        $(".datatables-basic").DataTable().ajax.reload();
        toastr.success("City updated successfully");
      },
      error: function (xhr) {
        toastr.error("Update failed: " + xhr.responseJSON?.message || "Server error");
      },
    });
  });

  // Delete Button Click
  $(document).on("click", ".delete-record", function () {
    deleteCityId = $(this).data("id");
    $("#confirmDeleteModal").modal("show");
  });

  // Confirm Delete
  $("#confirmDeleteBtn").on("click", function () {
    if (!deleteCityId) {
      toastr.error("No city selected.");
      return;
    }

    $.ajax({
      url: deleteUrl,
      type: "POST",
      data: {
        id: deleteCityId,
        _method: "DELETE",
        _token: $("meta[name='csrf-token']").attr("content"),
      },
      success: function (res) {
        $("#confirmDeleteModal").modal("hide");
        $(".datatables-basic").DataTable().ajax.reload(null, false);
        toastr.success(res.message || "City deleted successfully");
      },
      error: function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Delete failed");
      },
    });
  });
});
