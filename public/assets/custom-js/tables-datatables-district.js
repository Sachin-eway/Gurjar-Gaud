"use strict";

let districtCanvasEl, districtFv, deleteDistrictId;

$(document).ready(function () {
  const formAddNewDistrict = document.getElementById("districtForm");

  // Show Add District Modal
  setTimeout(() => {
    const newRecord = document.querySelector(".create-new"),
      canvasEl = document.querySelector("#add-new-record");

    if (newRecord && canvasEl) {
      newRecord.addEventListener("click", function () {
        districtCanvasEl = new bootstrap.Offcanvas(canvasEl);
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").empty();
        $("#districtForm").trigger("reset");
        if (typeof districtFv !== "undefined") {
          districtFv.resetForm(true);
        }
        districtCanvasEl.show();
      });
    }
  }, 200);

  // Form Validation
  if (formAddNewDistrict) {
    districtFv = FormValidation.formValidation(formAddNewDistrict, {
      fields: {
        name: {
          validators: {
            notEmpty: { message: "Please enter district name" },
          },
        },
        state_id: {
          validators: {
            notEmpty: { message: "Please select a state" },
          },
        },
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
      $.post(formAddNewDistrict.action, $(formAddNewDistrict).serialize(), function () {
        const canvasInstance = bootstrap.Offcanvas.getInstance(document.getElementById("add-new-record"));
        if (canvasInstance) canvasInstance.hide();

        $(".datatables-basic").DataTable().ajax.reload(null, false);
        toastr.success("District added successfully");
      }).fail(function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Server error");
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
        { data: "name" },
        { data: "state_name", defaultContent: "" },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            const editBtn = row.canEdit
              ? `<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon edit-record" data-id="${row.distt_id}"><i class="mdi mdi-pencil-outline"></i></a>`
              : "";
            const deleteBtn = row.canDelete
              ? `<a href="javascript:;" class="btn btn-sm btn-icon text-danger delete-record" data-id="${row.distt_id}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"><i class="mdi mdi-delete"></i></a>`
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

    $("div.head-label").html('<h5 class="card-title mb-0">Districts</h5>');
  }

  // Edit Modal
  $(document).on("click", ".edit-record", function () {
    const id = $(this).data("id");
    $.get(`/edit-district/${id}`, function (data) {
      $("#edit_name").val(data.name);
      $("#edit_state_id").val(data.state_id);
      $("#edit_id").val(data.distt_id);

      const editOffcanvas = new bootstrap.Offcanvas("#districtEdit");
      editOffcanvas.show();
    });
  });

  // Delete button handler
  $(document).on("click", ".delete-record", function () {
    deleteDistrictId = $(this).data("id");
    $("#confirmDeleteModal").modal("show");
  });

  // Confirm delete
  $("#confirmDeleteBtn").on("click", function () {
    if (!deleteDistrictId) {
      toastr.error("No district selected.");
      return;
    }

    $.ajax({
      url: deleteUrl,
      type: "POST",
      data: {
        id: deleteDistrictId,
        _method: "DELETE",
        _token: $("meta[name='csrf-token']").attr("content"),
      },
      success: function (res) {
        $("#confirmDeleteModal").modal("hide");
        $(".datatables-basic").DataTable().ajax.reload(null, false);
        toastr.success(res.message || "District deleted successfully");
      },
      error: function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Delete failed");
      },
    });
  });
});
