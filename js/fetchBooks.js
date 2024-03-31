$("document").ready(function() {
    const bookContainer  = document.getElementById("book-container");
    getBooks = () => {
        fetch(`json/books.json`)
        .then(response => response.json())
        .then(data => {
            let delay = 0.1
            data.books.forEach(book => {
                bookContainer.innerHTML += `
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="${delay}s">
                    <div class="course-item bg-light">
                        <div class="position-relative overflow-hidden">
                            <a href="#book"><img class="img-fluid" src="${book.image}" alt=""></a>
                        </div>
                        <div class="text-center p-4 pb-0">
                            <h5 class="mb-4">${book.title}</h5>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-user-tie text-primary me-2"></i>${book.author}</small>
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-star text-primary me-2"></i>${book.rating}</small>
                            <small class="flex-fill text-center py-2"><i class="fa fa-book-open text-primary me-2"></i>${book.genre}</small>
                        </div>
                    </div>
                </div> `
            });

            delay += 0.2;
        })
    }

    getBooks();
});