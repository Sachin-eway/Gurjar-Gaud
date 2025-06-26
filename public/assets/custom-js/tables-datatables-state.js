"use strict";

let offCanvasEl, fv;

// Document Ready
$(function () {
  const formAddNewRecord = document.getElementById("stateForm");
  const formEditRecord = document.getElementById("stateEditForm");

  setTimeout(() => {
    const newRecord = document.querySelector(".create-new"),
      offCanvasElement = document.querySelector("#add-new-record");

    if (newRecord) {
      newRecord.addEventListener("click", function () {
        offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").empty();
        $("#stateForm").trigger("reset");
        if (typeof fv !== "undefined") {
          fv.resetForm(true);
        }
        offCanvasEl.show();
      });
    }
  }, 200);

  // Add Form Validation
  if (formAddNewRecord) {
    fv = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: "Please enter state name",
            },
          },
        }
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
    }).on('core.form.valid', function () {
      $.post(addUrl, $("#stateForm").serialize(), function () {
        $(".offcanvas").offcanvas("hide");
        $(".datatables-basic").DataTable().ajax.reload();
      });
    });
  }

  // Edit Form Submit
  if (formEditRecord) {
    formEditRecord.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = $(this).serialize() + '&_method=PUT';
    
      $.ajax({
        url: updateUrl,
        method: "POST", // Laravel will treat as PUT due to _method
        data: formData,
        success: function () {
          $(".offcanvas").offcanvas("hide");
          $(".datatables-basic").DataTable().ajax.reload();
        },
      });
    });
    
  }

  // DataTable Init
  const dt_basic_table = $(".datatables-basic");
  let dt_basic;

  if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
      ajax: {
        url: stateUrl,
        dataSrc: function (json) {
          json.data.forEach((element, index) => {
            element.sequence_number = index + 1;
            element.canEdit = json.canEdit || false;
            element.canDelete = json.canDelete || false;
          });

          if (json.canAdd) {
            $(".create-new").removeClass("d-none");
          }

          return json.data;
        },
      },
      columns: [
        { data: null },
        { data: "sequence_number" },
        { data: "name" },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, full) {
            const editBtn = full.canEdit
              ? `<a href="javascript:;" data-id="${full.state_id}" data-name="${full.name}" class="btn btn-sm btn-text-secondary rounded-pill btn-icon edit-record"><i class="mdi mdi-pencil-outline"></i></a>`
              : "";
              const deleteBtn = full.canDelete
  ? `<a href="javascript:;" class="btn btn-sm btn-icon text-danger delete-record" 
       data-id="${full.state_id}" 
       data-bs-toggle="modal" 
       data-bs-target="#confirmDeleteModal">
       <i class="mdi mdi-delete"></i></a>`
  : "";

            return editBtn + deleteBtn;
          }
          
          
        },
      ],
      columnDefs: [
        {
          targets: 0,
          className: 'text-center',
          orderable: false,
          render: function () {
            return '<i class="mdi mdi-drag"></i>';
          }
        }
      ],
      dom: '<"card-header d-flex justify-content-between align-items-center flex-wrap"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: "collection",
          className: "btn btn-label-primary dropdown-toggle me-2 waves-effect",
          text: '<i class="mdi mdi-export-variant me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
          buttons: [
            { extend: "csv", className: "dropdown-item" },
            { extend: "excel", className: "dropdown-item" },
            { extend: "copy", className: "dropdown-item" }
          ]
        },
        {
          text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
          className: "create-new btn btn-primary d-none"
        }
      ]
    });

    $('div.head-label').html('<h5 class="card-title mb-0">States</h5>');
  }

  // Edit Record Button Click
  $(document).on("click", ".edit-record", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");

    $("#edit_id").val(id);
    $("#edit_name").val(name);

    const offCanvasEdit = new bootstrap.Offcanvas("#stateEdit");
    offCanvasEdit.show();
  });

  // Delete Record
  // DELETE STATE: Modal handler
let deleteStateId = null;

// When delete button is clicked, store ID and show modal
$(document).on("click", ".delete-record", function () {
  deleteStateId = $(this).data("id");
  $("#confirmDeleteModal").modal("show");
});

// When confirm button is clicked
$("#confirmDeleteBtn").on("click", function () {
  if (!deleteStateId) {
    toastr.error("No state selected.");
    return;
  }

  $.ajax({
    url: deleteUrl, // example: route('delete-state')
    type: "POST",
    data: {
      state_id: deleteStateId,
      _method: "DELETE",
      _token: $("meta[name='csrf-token']").attr("content"),
    },
    success: function (res) {
      $("#confirmDeleteModal").modal("hide");
      $(".datatables-basic").DataTable().ajax.reload(null, false);
      toastr.success(res.message || "State deleted successfully");
    },
    error: function (xhr) {
      const msg = xhr.responseJSON?.message || "Server error";
      toastr.error("Delete failed: " + msg);
    },
  });
});

  
  
});
