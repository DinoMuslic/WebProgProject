var IsAdmin = {
    validate: function(callback) {
        var currentId = Utils.get_from_localstorage("user").id;
        RestClient.get(
            "backend/professors/" + currentId,
            function(response) {
                // Assuming the response contains isAdmin property
                var isAdmin = response.isAdmin || false; // Default to false if isAdmin is not present in the response
                console.log("User is admin: " + isAdmin);
                callback(isAdmin); // Call the callback function with isAdmin value
            },
            function(error) {
                console.log("Error while validating admin status:", error);
                callback(false); // Call the callback function with false in case of an error
            }
        );
    }
}