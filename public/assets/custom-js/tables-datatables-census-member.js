"use strict";

$(function () {
  const form = document.getElementById("memberEditForm");

  if (form) {
    FormValidation.formValidation(form, {
      fields: {
        full_name: {
          validators: { notEmpty: { message: "Please enter full name" } },
        },
        gender: {
          validators: { notEmpty: { message: "Please select gender" } },
        },
        dob: {
          validators: {
            date: { format: "YYYY-MM-DD", message: "Invalid DOB" },
          },
        },
        mobile: {
          validators: {
            stringLength: {
              min: 10,
              max: 15,
              message: "Mobile number must be 10-15 digits",
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
      const formData = $("#memberEditForm").serialize();
      $.post(updateUrl, formData, function () {
        $("#memberEdit").offcanvas("hide");
        $(".datatables-members").DataTable().ajax.reload(null, false);
        toastr.success("Member updated successfully");
      }).fail(function (xhr) {
        toastr.error(xhr.responseJSON?.message || "Update failed");
      });
    });
  }

  const table = $(".datatables-members").DataTable({
    ajax: {
      url: dataUrl,
      dataSrc: function (json) {
        json.data.forEach((d, i) => d.sequence = i + 1);
        return json.data;
      },
    },
    columns: [
      { data: null },
      { data: "sequence" },
      { data: "full_name" },
      { data: "gender" },
      { data: "dob" },
      { data: "marital_status" },
      { data: "education" },
      { data: "occupation" },
      { data: "income_source" },
      { data: "mobile" },
      { data: "whatsapp" },
      { data: "identity_proof" },
      {
        data: null,
        render: function (data, type, row) {
          let html = "";

          if (row.canEdit) {
            html += `<button class="btn btn-sm btn-primary editBtn"
              data-id="${row.id}"
              data-census_form_id="${row.census_form_id}"
              data-full_name="${row.full_name || ''}"
              data-gender="${row.gender || ''}"
              data-dob="${row.dob || ''}"
              data-marital_status="${row.marital_status || ''}"
              data-education="${row.education || ''}"
              data-occupation="${row.occupation || ''}"
              data-income_source="${row.income_source || ''}"
              data-mobile="${row.mobile || ''}"
              data-whatsapp="${row.whatsapp || ''}"
              data-identity_proof="${row.identity_proof || ''}"
            >Edit</button> `;
          }

          if (row.canDelete) {
            html += `<button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}">Delete</button>`;
          }

          return html;
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
  });

  // ✅ Edit button now reads from data-* attributes (no AJAX needed)
  $(document).on("click", ".editBtn", function () {
    const $btn = $(this);
    $("#edit_id").val($btn.data("id"));
    $("#edit_census_form_id").val($btn.data("census_form_id"));
    $("#edit_full_name").val($btn.data("full_name"));
    $("#edit_gender").val($btn.data("gender"));
    $("#edit_dob").val($btn.data("dob"));
    $("#edit_marital_status").val($btn.data("marital_status"));
    $("#edit_education").val($btn.data("education"));
    $("#edit_occupation").val($btn.data("occupation"));
    $("#edit_income_source").val($btn.data("income_source"));
    $("#edit_mobile").val($btn.data("mobile"));
    $("#edit_whatsapp").val($btn.data("whatsapp"));
    $("#edit_identity_proof").val($btn.data("identity_proof"));

    new bootstrap.Offcanvas("#memberEdit").show();
  });

  // Delete logic
  let deleteId = null;

  $(document).on("click", ".deleteBtn", function () {
    deleteId = $(this).data("id");
    $("#confirmDeleteModal").modal("show");
  });

  $("#confirmDeleteBtn").on("click", function () {
    $.post(deleteUrl, {
      id: deleteId,
      _token: $("meta[name='csrf-token']").attr("content"),
    }, function () {
      $("#confirmDeleteModal").modal("hide");
      table.ajax.reload(null, false);
      toastr.success("Member deleted successfully");
    }).fail(function () {
      toastr.error("Failed to delete member");
    });
  });
});
