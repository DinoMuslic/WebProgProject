$("document").ready(function() {
    const staffContainer  = document.getElementById("staff-container");
    getStaff = () => {
        fetch(`json/staff.json`)
        .then(response => response.json())
        .then(data => {
            let delay = 0.1;
            data.staff.forEach(staff => {
                staffContainer.innerHTML += `
                <div class="col-lg-2 col-md-2 wow fadeInUp" data-wow-delay="${delay}s">
                    <div class="team-item bg-light">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="${staff.image}" alt="profile image">
                        </div>
                        
                        <div class="text-center p-4">
                            <h5 class="mb-0">${staff.name}</h5>
                            <small>${staff.email}</small>
                        </div>
                    </div>
                </div> `
                
                delay += 0.2;
            });
        })
    }

    getStaff();
});