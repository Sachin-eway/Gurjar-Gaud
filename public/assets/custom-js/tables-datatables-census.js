"use strict";

let offCanvasEl, fv;

$(function () {
  const formEditRecord = document.getElementById("censusEditForm");

  // -----------------------
  // Form Validation
  // -----------------------
  if (formEditRecord) {
    fv = FormValidation.formValidation(formEditRecord, {
      fields: {
        head_name: { validators: { notEmpty: { message: "Please enter head name" } } },
        gender: { validators: { notEmpty: { message: "Please select gender" } } },
        dob: {
          validators: {
            notEmpty: { message: "Please enter date of birth" },
            date: { format: "YYYY-MM-DD", message: "Invalid date format" },
          },
        },
        contact_number: {
          validators: {
            notEmpty: { message: "Please enter contact number" },
            digits: {},
            stringLength: {
              min: 10,
              max: 15,
              message: "Contact number must be 10-15 digits",
            },
          },
        },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({ rowSelector: ".form-floating" }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        autoFocus: new FormValidation.plugins.AutoFocus(),
      },
    }).on("core.form.valid", function () {
      const formData = $("#censusEditForm").serialize() + "&_method=PUT";

      $.post("/update-census-form", formData, function () {
        $(".offcanvas").offcanvas("hide");
        $(".datatables-basic").DataTable().ajax.reload(null, false);
        toastr.success("Record updated successfully.");
      }).fail(function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Failed to update record.");
      });
    });
  }

  // -----------------------
  // DataTable Init
  // -----------------------
  const dt_basic_table = $(".datatables-basic");

  if (dt_basic_table.length) {
    dt_basic_table.DataTable({
      ajax: {
        url: "/census-forms",
        dataSrc: function (json) {
          json.data.forEach((d, i) => d.sequence_number = i + 1);
          if (json.canAdd) $(".create-new").removeClass("d-none");
          return json.data;
        },
      },
      columns: [
        { data: null },
        { data: "sequence_number" },
        { data: "family_uid" },
        { data: "head_name" },
        { data: "gender" },
        { data: "dob" },
        { data: "contact_number" },
        { data: "email" },
        { data: "father_or_husband_name" },
        { data: "caste" },
        { data: "family_deity" },
        { data: "bank_account" },
        { data: "whatsapp" },
        { data: "identity_proof" },
        { data: "current_address" },  
        { data: "permanent_address" },
        { data: "total_members" },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, full) {
            const editBtn = full.canEdit
              ? `<a href="javascript:;" class="btn btn-sm btn-icon text-secondary edit-record"
                   data-id="${full.id}"
                   data-head_name="${full.head_name}"
                   data-gender="${full.gender}"
                   data-dob="${full.dob}"
                   data-contact_number="${full.contact_number}"
                   data-email="${full.email}"
                   data-family_uid="${full.family_uid}"
                   data-father_or_husband_name="${full.father_or_husband_name}"
                   data-caste="${full.caste}"
                   data-family_deity="${full.family_deity}"
                   data-bank_account="${full.bank_account}"
                   data-whatsapp="${full.whatsapp}"
                   data-identity_proof="${full.identity_proof}"
                   data-current_address="${full.current_address}"
                   data-permanent_address="${full.permanent_address}"
                   data-total_members="${full.total_members}">
                   <i class="mdi mdi-pencil-outline"></i></a>`
              : "";

            const deleteBtn = full.canDelete
              ? `<a href="javascript:;" class="btn btn-sm btn-icon text-danger delete-record"
                   data-id="${full.id}" data-bs-toggle="modal"
                   data-bs-target="#confirmDeleteModal">
                   <i class="mdi mdi-delete"></i></a>`
              : "";

            return editBtn + deleteBtn;
          },
        },
      ],
      columnDefs: [{
        targets: 0,
        className: "text-center",
        orderable: false,
        render: function () {
          return '<i class="mdi mdi-drag"></i>';
        },
      }],
      dom: '<"card-header d-flex justify-content-between align-items-center flex-wrap"<"head-label"><"dt-action-buttons text-end pt-3 pt-md-0">>t',
      displayLength: 10,
      lengthMenu: [10, 25, 50, 100],
    });

    $('div.head-label').html('<h5 class="card-title mb-0">Census Forms</h5>');
  }

  // -----------------------
  // Edit Button Trigger
  // -----------------------
  $(document).on("click", ".edit-record", function () {
    const fields = [
      "id", "head_name", "gender", "dob", "contact_number", "email",
      "family_uid", "father_or_husband_name", "caste", "family_deity",
      "bank_account", "whatsapp", "identity_proof", "current_address",
      "permanent_address", "total_members"
    ];

    fields.forEach(field => {
      $(`#edit_${field}`).val($(this).data(field));
    });

    const offCanvasEdit = new bootstrap.Offcanvas("#censusEdit");
    offCanvasEdit.show();
  });

  // -----------------------
  // Delete Modal Logic
  // -----------------------
  let deleteCensusId = null;

  $(document).on("click", ".delete-record", function () {
    deleteCensusId = $(this).data("id");
    $("#confirmDeleteModal").modal("show");
  });

  $("#confirmDeleteBtn").on("click", function () {
    if (!deleteCensusId) return;

    $.ajax({
      url: "/delete-census-form",
      type: "POST",
      data: {
        id: deleteCensusId,
        _method: "DELETE",
        _token: $("meta[name='csrf-token']").attr("content"),
      },
      success: function (res) {
        $("#confirmDeleteModal").modal("hide");
        $(".datatables-basic").DataTable().ajax.reload(null, false);
        toastr.success(res.message || "Record deleted successfully");
      },
      error: function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Delete failed");
      },
    });
  });
});
