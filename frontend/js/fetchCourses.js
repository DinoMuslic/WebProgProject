$("document").ready(function() {
    const coursesContainer  = document.getElementById("courses-container");
    getCourses = () => {
        fetch(`json/courses.json`)
        .then(response => response.json())
        .then(data => {
            let delay = 0.1;
            data.courses.forEach(cousre => {
                coursesContainer.innerHTML += `
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="${delay}s">
                    <a href="#course">
                        <div class="course-item bg-light">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid" src="${cousre.image}" alt="">
                            </div>
                            <div class="text-center p-4 pb-0">
                                <h5 class="mb-4">${cousre.title}</h5>
                            </div>
                            <div class="d-flex border-top">
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-user-tie text-primary me-2"></i>${cousre.professor}</small>
                                <small class="flex-fill text-center border-end py-2"><i class="fa fa-flask text-primary me-2"></i>${cousre.department}</small>
                                <small class="flex-fill text-center py-2"><i class="fa fa-user text-primary me-2"></i>${cousre.students}</small>
                            </div>
                        </div>
                    </a>
                </div>
                `
                delay += 0.2;
            })
        })
    }

    getCourses();
});