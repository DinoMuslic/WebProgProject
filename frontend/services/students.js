var StudentService = {
    reload_students_datatable: function () {
      Utils.get_datatable(
        "tbl_students",
        Constants.API_BASE_URL + "backend/students",
        [
          { data: "id" },
          { data: "first_name" },
          { data: "last_name" },
          { data: "email" },
          { data: "faculty" },
          { data: "department" },
          { data: "enrolment_year" },
          { data: "action" }
        ]
      );
    },

    open_edit_student_modal : function(student_id) {
        RestClient.get(
          'backend/students/' + student_id,
          function(data) {
            $('#add-student-modal').modal("toggle");
            $("#add-student-form input[name='id']").val(data.id);
            $("#add-student-form input[name='first_name']").val(data.first_name);
            $("#add-student-form input[name='last_name']").val(data.last_name);
            $("#add-student-form input[name='email']").val(data.email);
            $("#add-student-form input[name='faculty']").val(data.faculty);
            $("#add-student-form input[name='department']").val(data.department);
            $("#add-student-form input[name='enrolment_year']").val(data.enrolment_year);
          }
        )
    },

    delete_student : function(student_id) {
      if(confirm("Do you really want to delete this student?")) {
        RestClient.delete(
          "backend/students/delete/" + student_id,
          {},
          function(data) {
            toastr.success("student deleted successfully");
            StudentService.reload_students_datatable();
        });
      }
    },
}