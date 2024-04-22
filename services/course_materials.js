var MaterialService = {
    reload_materials_datatable: function () {
      Utils.get_datatable(
        "tbl_materials",
        Constants.API_BASE_URL + "backend/get_materials.php",
        [
          { data: "id" },
          { data: "course_id" },
          { data: "title" },
          { data: "contents" },
          { data: "action" }
        ]
      );
    },

    open_edit_material_modal : function(material_id) {
        RestClient.get(
          'backend/get_material.php?id=' + material_id,
          function(data) {
            $('#add-material-modal').modal("toggle");
            $("#add-material-form input[name='id']").val(data.id);
            $("#add-material-form input[name='course_id']").val(data.course_id);
            $("#add-material-form input[name='title']").val(data.title);
            $("#add-material-form textarea[name='contents']").val(data.contents);
          }
        )
    },

    delete_material : function(material_id) {
      if(confirm("Do you really want to delete this material?")) {
        RestClient.delete(
          "backend/delete_material.php?id=" + material_id,
          {},
          function(data) {
            toastr.success("material deleted successfully");
            MaterialService.reload_materials_datatable();
        });
      }
    },
}