$("document").ready(function() {

    let dropDownBtns = document.querySelectorAll('.dropdown-item');
    for(let i = 0; i < dropDownBtns.length; i++) {
        dropDownBtns[i].addEventListener('click', () => {
            setTimeout(() => {
                location.reload();
            }, 10);
        });
    }

    let path = window.location.href;
    let page = path.split("-").pop();

    console.log(JSON.stringify(`${page}.json`));
    

    let table = document.getElementById('tbl');
    table.id = 'tbl';
    table.className = 'table table-hover table-bordered text-center';

    
    let thead = document.createElement('thead');
    let tr = document.createElement('tr');
    

    // switch(page) {
    //     case 'users': var headers = ['ID', 'Name', 'Image','Email', 'Courses',  'Phone', 'Faculty', 'Department', 'Action']; break;
    //     case 'books': var headers = ['ISBN', 'Title', 'Author', 'Genre', 'Rating', 'Action']; break;
    //     case 'staff': var headers = ['ID', 'Name', 'Image', 'Email', 'Department', 'Courses', 'Action']; break;
    // }



    let tbody = document.createElement('tbody');
    table.append(tbody);

    drawTable = (json) => {
        json.forEach((user, index) => {
            let tr = document.createElement('tr');

            for (let attribute in user) {
                if(attribute !== 'image') {
                    let cell = document.createElement('td');
                    cell.innerHTML = user[attribute];
                    tr.append(cell);
                } else {
                    let cell = document.createElement('td');
                    cell.innerHTML =  `<img style="height 35px; width: 35px" src='${user[attribute]}' alt='user-image'/>`;
                    tr.append(cell);
                }
                
            }
            let actionCell = document.createElement('td');
                actionCell.className = 'w-25';

                ['Update', 'Delete'].forEach(action => {
                    let button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-sm btn-outline-' + (action === 'Update' ? 'primary' : 'danger');
                    button.textContent = action;
                    button.style.marginRight = '10px';
                    button.style.marginBottom = '5px';
                    actionCell.append(button);
                });
                tr.append(actionCell);
                tbody.append(tr);
    });
    }

    getJSONData = (page) => {
        fetch(`json/${page}.json`)
                .then(response => response.json())
                .then(data => {
                    let json = data[page];
                    let headers = Object.keys(json[0]);
                    headers.forEach(header => {
                        let th = document.createElement('th');
                        th.scope = 'col';
                        th.textContent = header;
                        tr.append(th);
                    });
                    thead.append(tr);
                    table.append(thead);

                    let th = document.createElement('th');
                    th.scope = 'col';
                    th.textContent = "action";
                    tr.append(th);
                    drawTable(json);
            });
    }

    getJSONData(page);
});


