var CourseService = {
    reload_courses_datatable: function () {
      Utils.get_datatable(
        "tbl_courses",
        Constants.API_BASE_URL + "backend/get_courses.php",
        [
          { data: "id" },
          { data: "title" },
          { data: "action" }
        ]
      );
    },

    open_edit_course_modal : function(course_id) {
        RestClient.get(
          'backend/get_course.php?id=' + course_id,
          function(data) {
            $('#add-course-modal').modal("toggle");
            $("#add-course-form input[name='id']").val(data.id);
            $("#add-course-form input[name='title']").val(data.title);
          }
        )
    },

    delete_course : function(course_id) {
      if(confirm("Do you really want to delete this course?")) {
        RestClient.delete(
          "backend/delete_course.php?id=" + course_id,
          {},
          function(data) {
            toastr.success("Course deleted successfully");
            CourseService.reload_courses_datatable();
        });
      }
    },

    
}