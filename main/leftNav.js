$(document).ready(function () {
    retrieveAllSelect();
});
$('#addCategory').submit(function (e) { 
    e.preventDefault();
    const value = $("#addCategoryItem").val().trim();
    if(value.length === 0){
        $("#alertNoCategoryName").removeClass("d-none");
        return;
    }
    $("#alertNoCategoryName").addClass("d-none");
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "insertCategory",
            category: value,
        }
    }).done(res=>{
            $("#addCategoryItem").val('');
            const item = 
            `<option value="${res}">
            ${value}
            </option>`;
            $('#category').append(item);
            const item2 = 
            `<a class="nav-link " href="#">
            <i class="fas fa-eraser btn btn-sm deleteCategory"></i>
            <i class="fas fa-bullseye"></i>
            <span class="text"> ${value} </span>
            <p class="d-none idcategory"> ${res} </p>
            </a>`;
            $('#leftNavCategories').append(item2);
    }).fail(res=>{
            console.log(res.statusText);
    })
});
$('#allTask').click(function (e) { 
    location.reload();
    return false;   
});
$('#today').click(function (e) { 
    const date = new Date();
    const time = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    // console.log(time);
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "todayFilter",
            time: time
        }
    }).done(res=>{
        // console.log(res);
        document.title = "Today's Job";
        updatePanel(res);
        retrieveAllSelect("category");
        $('#bigTabLabel').text("Today");
    })
});
$('#nextSeven').click(function (e) { 
    const date = new Date();
    const curr = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    const days = 7; // Days you want to subtract
    const date2 = new Date(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const target = date2.getFullYear() + "-" + (date2.getMonth()+1) + "-" + date2.getDate();
    console.log(target);
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "timeFilter",
            curr: curr,
            target: target
        }
    }).done(res=>{
        // console.log(res);
        document.title = "Next Seven Days";
        updatePanel(res);
        retrieveAllSelect("category");
        $('#bigTabLabel').text("Next Seven Days");
    }).fail(res=>{
        console.log(res.responseText);
    })
});
$('#leftNavCategories').on('click', '.nav-link', reloadTodo);
function reloadTodo(e) {
    // console.log(this.childNodes); 
    // console.log(); 
    const value = this.childNodes[7].textContent.trim();
    // console.log(e.target.childNodes);
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "categoryFilter",
            idcategory: value
        }
    }).done(res=>{
        // console.log(this);
        // console.log(this.getElementsByClassName("text"));
        $('#bigTabLabel').text(this.getElementsByClassName("text")[0].innerText);
        updatePanel(res);
        updateSelect(value);
    }).fail(res=>{
        console.log(res.responseText);
    })
 }

$('#leftNavCategories').on('click', '.deleteCategory', deleteCategory);
function deleteCategory(e) {
    e.stopPropagation();
    if(confirm("You are about to delete everything under this category. Are you sure about it?")){
       const idcategory = this.parentElement.getElementsByClassName("idcategory")[0].innerText.trim();
       $.ajax({
           type: "post",
           url: "../util/update_database.php",
           data: {
             function: "deleteCategory",
             idcategory: idcategory
           }
       }).done(res=>{
            location.reload();
       });
    }else{
        console.log("Not deleted"); 
    }

}

function retrieveAllSelect(selectField) { 
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "getAllCategories",
        }
    }).done(res=>{
        $('#'+ selectField).empty();
        res = JSON.parse(res);
        // console.log(res);
        for(let i of res){
            $('#'+ selectField).append(`
            <option value="${i.idcategory}">
                ${i.category}
            </option>
            `)
        }
    }).fail(res=>{
        console.log(res.responseText);
    })
}
function updateSelect(value) {
    $('#category').empty();
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "getCategory",
            idcategory: value
        }
    }).done( res=>{
        // console.log(res);
        res = JSON.parse(res);
        console.log(res);
        
        document.title = res.category;
        $('#category').append(`
        <option value="${res.idcategory}">
            ${res.category}
        </option>
        `);
    } ).fail(res=>{
            console.log(res.responseText);
        }
    )
    $('#category').val(value);
}

 function updatePanel(res){
    $('#incompletePanel').empty();
    $('#completedPanel').empty();
    res = JSON.parse(res)
    // console.log(res);
    let countC = 0, countIC = 0;
    for(let i of res){
        // console.log(i['complete']);
        if(i['complete'] == 0){
            const item = 
            `<div>
                <div class="taskitem" data-toggle="modal" data-target="#modal" type="button">
                    <input type="hidden" class="idcategory" value="${res.idcategory} " >
                    <div class="p-2">
                        <span class="d-none">${i['idtask']}</span> 
                        <i class="far fa-check-square check"></i>
                        <span class="titletask">${i['title']}</span>
                        <a type="button" class="btn btn-sm delete"> <i class="fas fa-eraser"></i> </a>
                    </div>
                    <div class="category d-flex justify-content-end">
                        <span class="categorytask">${i['category']}</span>
                        <span class="ml-auto duedate">
                            ${i['duedate']}
                        </span>
                    </div>
                    <span class="taskdescription d-none">
                        ${i['description']}
                    </span>
                </div>
            </div>`;
            $('#incompletePanel').append(item);
            countIC++;
        }else{
            const item = 
            `<div>
                <div class="taskitem" data-toggle="modal" data-target="#modal" type="button">
                    <input type="hidden" class="idcategory" value="${res.idcategory} " >
                    <div class="p-2 completed">
                        <span class="d-none">${i['idtask']}</span> 
                        <i class="fas fa-check-square check"></i>
                        <span class="titletask">${i['title']}</span>
                        <a type="button" class="btn btn-sm delete"> <i class="fas fa-eraser"></i> </a>
                    </div>
                    <div class="category d-flex justify-content-end">
                        <span class="categorytask">${i['category']}</span>
                        <span class="ml-auto duedate">
                            ${i['duedate']}
                        </span>
                    </div>
                    <span class="taskdescription d-none">
                        ${i['description']}
                    </span>
                </div>
            </div>`;
            $('#completedPanel').append(item);
            countC++;
        }
    }
    $('#numCompleted').text(countC.toString());
    // $('#numCompleted').val(100);
    $('#numIncompleted').text(countIC.toString());
}