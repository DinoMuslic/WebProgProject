$("document").ready(function() {
    
    document.getElementById("form").addEventListener("submit", function (event) {
        event.preventDefault();
       
    });

    const loginBtn = document.getElementById("loginBtn");
    loginBtn.addEventListener('click', () => {
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let br = false;

        fetch('./json/login.json')
        .then(result => result.json())
        .then(data => {
            for(let i = 0; i < data.users.length; i++) {
                if(data.users[i].name === username && data.users[i].password === password) {
                    console.log("Successful login!");
                    br = true;
                    break;
                }
            }
            if(!br) {
                console.log("Wrong username or password!")
            }
        })
    })
});