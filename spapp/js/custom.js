$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);


  var app = $.spapp({
    defaultView  : "#home",
    templateDir  : "./pages/",
    pageNotFound : "#404"
  });
  
  app.route({
    view : "home",
    load : "home.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "courses",
    load : "courses.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "course",
    load : "course.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "dashboard-users",
    load : "dashboard-users.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "dashboard-books",
    load : "dashboard-books.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "dashboard-staff",
    load : "dashboard-staff.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "staff",
    load : "staff.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "library",
    load : "library.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "book",
    load : "book.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "user-profile",
    load : "user-profile.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "register",
    load : "register.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "login",
    load : "login.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "add_tables",
    load : "add_tables.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "get_tables",
    load : "get_tables.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.route({
    view : "404",
    load : "404.html",
    onCreate: function() {  },
    onReady: function() {  }
  });

  app.run();
});