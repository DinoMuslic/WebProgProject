var BookService = {
    reload_books_datatable: function () {
      Utils.get_datatable(
        "tbl_books",
        Constants.API_BASE_URL + "backend/books",
        [
          { data: "id" },
          { data: "title" },
          { data: "author" },
          { data: "genre" },
          { data: "publication_year" },
          { data: "pdf_link" },
          { data: "action" }
        ]
      );
    },

    open_edit_book_modal : function(book_id) {
        RestClient.get(
          'backend/books/' + book_id,
          function(data) {
            $('#add-book-modal').modal("toggle");
            $("#add-book-form input[name='id']").val(data.id);
            $("#add-book-form input[name='course_id']").val(data.course_id);
            $("#add-book-form input[name='title']").val(data.title);
            $("#add-book-form input[name='author']").val(data.author);
            $("#add-book-form input[name='genre']").val(data.genre);
            $("#add-book-form input[name='publication_year']").val(data.publication_year);
            $("#add-book-form input[name='pdf_link']").val(data.pdf_link);
          }
        )
    },

    delete_book : function(book_id) {
      if(confirm("Do you really want to delete this book?")) {
        RestClient.delete(
          "backend/books/delete/" + book_id,
          {},
          function(data) {
            toastr.success("book deleted successfully");
            BookService.reload_books_datatable();
        });
      }
    },

    
}