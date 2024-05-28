var CourseService = {
    reload_courses_datatable: function () {
      Utils.get_datatable(
        "tbl_courses",
        Constants.API_BASE_URL + "backend/courses",
        [
          { data: "id" },
          { data: "title" },
          { data: "faculty" },
          { data: "department" },
          { data: "professor" },
          { data: "image" },
          { data: "action" }
        ]
      );
    },

    open_edit_course_modal : function(course_id) {
        RestClient.get(
          'backend/courses/' + course_id,
          function(data) {
            $('#add-course-modal').modal("toggle");
            $("#add-course-form input[name='id']").val(data.id);
            $("#add-course-form input[name='title']").val(data.title);
            $("#add-course-form input[name='faculty']").val(data.faculty);
            $("#add-course-form input[name='department']").val(data.department);
            $("#add-course-form input[name='image']").val(data.image);
          }
        )
    },

    delete_course : function(course_id) {
      if(confirm("Do you really want to delete this course?")) {
        RestClient.delete(
          "backend/courses/delete/" + course_id,
          {},
          function(data) {
            toastr.success("Course deleted successfully");
            CourseService.reload_courses_datatable();
        });
      }
    },
}