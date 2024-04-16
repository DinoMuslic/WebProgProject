var CourseService = {
    reload_courses_datatable: function () {
      Utils.get_datatable(
        "tbl_courses",
        Constants.API_BASE_URL + "backend/get_courses.php",
        [
          { data: "title" },
        ]
      );
    },
}