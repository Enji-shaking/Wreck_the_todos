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
    const time = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    $('#duedate').val(time);
    retrieveAllSelect('category');
});

$('.plusToggle').click(function (e) { 
    e.preventDefault();
    $('#description').toggleClass('d-flex');
    $('#description').slideToggle();
});

// Manipulate items
$('.canvas').on('click', '.check', completing);
$('.canvas').on('click', '.delete', deleting);
$('.canvas').on('click', '.taskitem', displayingDetail);


function completing(e) {
    e.stopPropagation();
    console.log("love");
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
    $(this).toggleClass('far');
    $(this).toggleClass('fas');
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            idtask: $(this).parent().children(":first").text(),
            completedChangeTo: completed?0:1,
            function: "completeTask"
        }
    }).done(res=>{
        // console.log(res);
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
        // console.log(res);
   }).fail(res=>{
    console.log(res.responseText);
})
}
var toBeChange = null;
function displayingDetail(e) { 
    toBeChange = this;
    // console.log(this);
    // console.log(this.getElementsByClassName("titletask"));
    // console.log(this.getElementsByClassName("duedate"));
    retrieveAllSelect('updateCategories');
    const idtask = this.getElementsByClassName("idtask")[0].innerText;
    console.log(idtask);
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data: {
            idtask: idtask,
            function: 'getTask'
        }
    }).done(res=>{
        // console.log(res);
        // console.log(JSON.parse(res));
        res = JSON.parse(res);
        $('#updateTaskId').val(res.idtask);
        $('#updateTitle').val(res.title);
        $('#updateduedate').val(res.duedate);
        $('#updateDescription').val(res.description);
        $('#updateCategories').val(res.idcategory);
    }).fail(res=>{
        console.log(res.responseText);
    })
    ;
    // console.log(this.getElementsByClassName("idcategory")[0].defaultValue.trim());
    // console.log(this.getElementsByClassName("duedate")[0].innerText);
    // console.log(this.getElementsByClassName("taskdescription")[0].innerText.trim());
 }

$('#updateForm').submit(function (e) { 
    e.preventDefault();
    // data validation
    var values = {};
    $.each($('#updateForm').serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });
    console.log(values);
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
        // console.log(res);
        // console.log(toBeChange);
        // console.log(toBeChange.getElementsByClassName("titletask"));
        toBeChange.getElementsByClassName("titletask")[0].innerText = res.title;
        toBeChange.getElementsByClassName("duedate")[0].innerText = res.duedate;
        toBeChange.getElementsByClassName("categorytask")[0].innerText = res.category;
        toBeChange.getElementsByClassName("taskdescription")[0].innerText = res.description;
        // console.log(toBeChange);
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
    $("#alertNoTitle").addClass("d-none");
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
        // console.log(res);
        res = JSON.parse(res);
        let item = `
        <div>
            <div class="taskitem" data-toggle="modal" data-target="#modal" type="button">
                <input type="hidden" class="idcategory" value="${res.idcategory} " >
                <div class="p-2">
                    <span class="d-none idtask"> ${res.idtask} </span> 
                    <i class="far fa-check-square check"></i>
                    <span class="titletask">${res.title}</span>
                    <a type="button" class="btn btn-sm delete"> <i class="fas fa-eraser"></i> </a>
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

            // <div class="detail" style="display:none;">
            //     ${res.description}
            // </div>
        $('#incompletePanel').prepend(item);
        $('#title').val('');
        $('#descriptionTextArea').val('');
        $('#numIncompleted').html(parseInt($('#numIncompleted').html())+1);
    }).fail(res=>{
        console.log(res.responseText);
    })
});

