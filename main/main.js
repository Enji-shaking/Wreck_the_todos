$(document).ajaxStart(function(){
    $.LoadingOverlay("show");
});
$(document).ajaxStop(function(){
    $.LoadingOverlay("hide");
});

$(document).ready(function () {
    $('#description').hide();
    $('.detail').hide();
    const date = new Date();
    const time = date.getFullYear() + "-" + 
        ((date.getMonth()+1)<10?"0":"") +
        ((date.getMonth()+1) + "-") +
        (date.getDate()<10?"0":"") + 
        date.getDate();

    $('#duedate').val(time);
    retrieveAllSelect('category');
});

$('.plusToggle').click(function (e) { 
    e.preventDefault();
    $('#description').toggleClass('d-flex');
    if ($(window).width() > 767) {
        $('#description').slideToggle();
    } else {
        $('#description, #sm-add-categories').slideToggle();
    }
});

// Manipulate items
$('.canvas').on('click', '.check', completing);
$('.canvas').on('click', '.delete', deleting);
$('.canvas').on('click', '.taskitem', displayingDetail);


function completing(e) {
    e.stopPropagation();
    const completed = $(this).parent().hasClass('completed')?1:0;
    if(!completed){
        $('#completedPanel').prepend($(this).parent().parent().parent());
        $('#numCompleted').html(parseInt($('#numCompleted').html())+1);
        $('#numIncompleted').html(parseInt($('#numIncompleted').html())-1);
    }else{
        $('#incompletePanel').append($(this).parent().parent().parent());
        $('#numCompleted').html(parseInt($('#numCompleted').html())-1);
        $('#numIncompleted').html(parseInt($('#numIncompleted').html())+1);
    }
    $(this).parent().toggleClass('completed');
    $(this).toggleClass('far fa-square');
    $(this).toggleClass('fas fa-check-square');
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            idtask: $(this).parent().children(":first").text(),
            completedChangeTo: completed?0:1,
            function: "completeTask"
        }
    }).done(res=>{

    }).fail(res=>{
        console.log(res.responseText);
    })
}

function deleting(e) {
    e.stopPropagation(); 
    if($(this).parent().hasClass('completed')){
        $('#numCompleted').html(parseInt($('#numCompleted').html())-1);
    }else{
        $('#numIncompleted').html(parseInt($('#numIncompleted').html())-1);
    }
    $(this).parent().parent().parent().remove();
   $.ajax({
       type: "post",
       url: "../util/update_database.php",
       data:  {
           idtask: $(this).parent().children(":first").text(),
           function: "deleteTask"
       }
   }).done(res=>{
    
   }).fail(res=>{
    console.log(res.responseText);
})
}
var toBeChange = null;
function displayingDetail(e) { 
    toBeChange = this;
    retrieveAllSelect('updateCategories');
    const idtask = this.getElementsByClassName("idtask")[0].innerText;
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data: {
            idtask: idtask,
            function: 'getTask'
        }
    }).done(res=>{
        res = JSON.parse(res);
        $('#updateTaskId').val(res.idtask);
        $('#updateTitle').val(res.title);
        $('#updateduedate').val(res.duedate);
        $('#updateDescription').val(res.description);
        $('#updateCategories').val(res.idcategory);
    }).fail(res=>{
        console.log(res.responseText);
    });
}

$('#updateForm').submit(function (e) { 
    e.preventDefault();
    // data validation
    var values = {};
    $.each($('#updateForm').serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });
    if(values["title"].trim().length === 0){
        $("#alertNoTitleUpdate").removeClass("d-none");
        return;
    }
    $("#alertNoTitleUpdate").addClass("d-none");
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "updateTask",
            title: values["title"],
            idcategory: values["idcategory"],
            description: values["description"],
            duedate: values["duedate"],
            idtask: values["idtask"]
        }
    }).done(res=>{
        res = JSON.parse(res);
        toBeChange.getElementsByClassName("titletask")[0].innerText = res.title;
        toBeChange.getElementsByClassName("duedate")[0].innerText = res.duedate;
        toBeChange.getElementsByClassName("categorytask")[0].innerText = res.category;
        toBeChange.getElementsByClassName("taskdescription")[0].innerText = res.description;
        toBeChange = null;
    });

    $('#modal').modal('toggle');
    
});

$('#inputTask').submit(function (e) { 
    e.preventDefault();
    var values = {};
    values['idcategory']='';
    $.each($('#inputTask').serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });
    if(values["title"].trim().length === 0){
        $("#alertNoTitle").removeClass("d-none");
        return;
    }
    $("#alertNoCategory").addClass("d-none");
    if(values["idcategory"].trim().length === 0){
        $("#alertNoCategory").removeClass("d-none");
        return;
    }
    $("#alertNoCategory").addClass("d-none");

    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "insertTask",
            title: values["title"],
            idcategory: values["idcategory"],
            description: values["description"],
            duedate: values["duedate"]
        }
    }).done(res=>{
        res = JSON.parse(res);
        let item = `
        <div>
            <div class="taskitem" data-toggle="modal" data-target="#modal">
                <input type="hidden" class="idcategory" value="${res.idcategory} " >
                <div class="p-2 d-flex flex-row align-items-center">
                    <span class="d-none idtask"> ${res.idtask} </span> 
                    <i class="far fa-square check"></i>
                    <span class="titletask flex-grow-1">${res.title}</span>
                    <a type="button" class="btn btn-sm btn-outline-info delete"> <i class="fas fa-eraser"></i> </a>
                </div>
                <div class="category d-flex justify-content-end">
                    <span class="categorytask">${(res.category == null?"":res.category)}</span>
                    <span class="ml-auto duedate">${res.duedate}</span>
                </div>
                <span class="taskdescription d-none">
                    ${res.description}
                </span>
            </div>
        </div>`;


        $('#incompletePanel').prepend(item);
        $('#title').val('');
        $('#descriptionTextArea').val('');
        $('#numIncompleted').html(parseInt($('#numIncompleted').html())+1);
    }).fail(res=>{
        console.log(res.responseText);
    })
});

$(window).on('resize', function() {
    if ($(window).width() > 767) {
        $('#sm-add-categories').slideUp();
        $('#select-list').modal('hide');
    } else {
        if ($('#description').css('display') != "none") {
            $('#sm-add-categories').slideDown();
        }
    }
});

$('#sm-add-categories').submit(function (e) { 
    e.preventDefault();
    const value = $("#sm-new-category").val().trim();
    if(value.length === 0){
        $("#sm-add-categories .alert").removeClass("d-none");
        return;
    }
    $("#sm-add-categories .alert").addClass("d-none");
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "insertCategory",
            category: value,
        }
    }).done(res=>{
            $("#sm-new-category").val('');
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

            let count = $('input[name=list]').length + 1;
            const radio = `<div class="form-check"><input class="form-check-input" type="radio" name="list" id="list${count}" value="${res}"><label class="form-check-label" for="list${count}">${value}</label></div>`;
            $("#list-options").append(radio);

    }).fail(res=>{
            console.log(res.statusText);
    })
});
