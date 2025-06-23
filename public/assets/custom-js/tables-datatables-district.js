"use strict";

let districtCanvasEl, districtFv;

$(document).ready(function () {
  const formAddNewDistrict = document.getElementById("districtForm");

  setTimeout(() => {
    const newRecord = document.querySelector(".create-new"),
      canvasEl = document.querySelector("#add-new-record");

    if (newRecord) {
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

  if (formAddNewDistrict) {
    districtFv = FormValidation.formValidation(formAddNewDistrict, {
      fields: {
        name: {
          validators: {
            notEmpty: { message: "Please enter district name" }
          }
        },
        state_id: {
          validators: {
            notEmpty: { message: "Please select a state" }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: "",
          rowSelector: ".form-floating"
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    });
  }

  const dt_table = $(".datatables-basic");
  let dt;

  if (dt_table.length) {
    dt = dt_table.DataTable({
      ajax: {
        url: fetchUrl,
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
        }
      },
      columns: [
        { data: null },
        { data: "sequence_number" },
        { data: "name" },
        { data: "state_name" },
        {
          data: "status",
          render: function (data, type, row) {
            return `
              <label class="switch switch-primary">
                <input type="checkbox" class="switch-input status_${row.id}" onclick="changeStatus(${row.id})" data-id="${row.id}" data-url="${changeStatusURl}" ${data == 1 ? "checked" : ""}>
                <span class="switch-toggle-slider">
                  <span class="switch-on"></span>
                  <span class="switch-off"></span>
                </span>
              </label>`;
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, full) {
            const editBtn = full.canEdit
              ? `<a href="javascript:;" data-id="${full.id}" class="btn btn-sm btn-text-secondary rounded-pill btn-icon edit-record"><i class="mdi mdi-pencil-outline"></i></a>`
              : "";
            const deleteBtn = full.canDelete
              ? `<a href="javascript:;" class="dropdown-item text-danger delete-record" data-url="${deleteUrl}" data-id="${full.id}"><i class="mdi mdi-delete"></i></a>`
              : "";
            return editBtn || deleteBtn ? editBtn + deleteBtn : "Permission Denied";
          }
        }
      ],
      columnDefs: [
        {
          targets: 0,
          className: "text-center",
          orderable: false,
          render: () => '<i class="mdi mdi-drag"></i>'
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
            { extend: "csv", className: "dropdown-item", text: '<i class="mdi mdi-file-document-outline me-1"></i>CSV' },
            { extend: "excel", className: "dropdown-item", text: '<i class="mdi mdi-file-excel-outline me-1"></i>Excel' },
            { extend: "copy", className: "dropdown-item", text: '<i class="mdi mdi-content-copy me-1"></i>Copy' }
          ]
        },
        {
          text: '<i class="mdi mdi-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
          className: "create-new btn btn-primary d-none"
        }
      ]
    });

    $('div.head-label').html('<h5 class="card-title mb-0">Districts</h5>');
  }
});
