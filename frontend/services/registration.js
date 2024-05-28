var RegistrationService = {
    registerStudent: function(data) {
        RestClient.post(
            "backend/students/add",
            data,
            function (response) {
              toastr.success("Registation Sucessful!");

            },
            function (error) {
              toastr.error("An Error occurred!");
            }
          );
    },

    registerProfessor: function(data) {
      RestClient.post(
          "backend/professors/add",
          data,
          function (response) {
            toastr.success("Registation Sucessful!");

          },
          function (error) {
            toastr.error("An Error occurred!");
          }
        );
  },
}