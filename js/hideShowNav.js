$("document").ready(function() {

    let reloadBtns = document.querySelectorAll('.reloadBtn');
    for(let i = 0; i < reloadBtns.length; i++) {
        reloadBtns[i].addEventListener('click', () => {
            setTimeout(() => {
                location.reload();
            }, 10);
        });
    }

    let path = window.location.href;
    let page = path.split("#").pop();

    if(page === 'login' || page === 'register') {
        $('#nav').hide();
    } else {
        $('#nav').show();
    }

});