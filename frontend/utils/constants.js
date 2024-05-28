var Constants = {
    get_api_base_url: function () { 
        if(location.hostname == 'localhost') {
            return 'http://localhost:80/WebProgProject/';
        } else {
            return "https://goldfish-app-wfyod.ondigitalocean.app/";
         }
    
     } 
    // API_BASE_URL: 'http://localhost:80/WebProgProject/',
}