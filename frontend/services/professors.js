var ProfessorService = {
    reload_professors_datatable: function () {
      Utils.get_datatable(
        "tbl_professors",
        Constants.API_BASE_URL + "backend/professors",
        [
          { data: "id" },
          { data: "first_name" },
          { data: "last_name" },
          { data: "email" },
          { data: "faculty" },
          { data: "department" },
          { data: "salary" },
          { data: "isAdmin" },
          { data: "action" }
        ]
      );
    },

    open_edit_professor_modal : function(professor_id) {
        RestClient.get(
          'backend/professors/' + professor_id,
          function(data) {
            $('#add-professor-modal').modal("toggle");
            $("#add-professor-form input[name='id']").val(data.id);
            $("#add-professor-form input[name='first_name']").val(data.first_name);
            $("#add-professor-form input[name='last_name']").val(data.last_name);
            $("#add-professor-form input[name='email']").val(data.email);
            $("#add-professor-form input[name='faculty']").val(data.faculty);
            $("#add-professor-form input[name='department']").val(data.department);
            $("#add-professor-form input[name='isAdmin']").val(data.isAdmin);
            $("#add-professor-form input[name='salary']").val(data.salary);
          }
        )
    },

    delete_professor : function(professor_id) {
      if(confirm("Do you really want to delete this professor?")) {
        RestClient.delete(
          "backend/professors/delete/" + professor_id,
          {},
          function(data) {
            toastr.success("professor deleted successfully");
            ProfessorService.reload_professors_datatable();
        });
      }
    },
}