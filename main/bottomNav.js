$('.bottom div:first-child').click(function (e) {
    const date = new Date();
    const time = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "todayFilter",
            time: time
        }
    }).done(res=>{
        document.title = "Today's Job";
        updatePanel(res);
        retrieveAllSelect("category");
        $('#bigTabLabel').text("Today");
    })
});

$('.bottom div:nth-child(3)').click(function (e) { 
    location.reload();
    return false;   
});

$('.bottom div:nth-child(2)').click(function (e) { 
    const date = new Date();
    const curr = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    const days = 7; // Days you want to subtract
    const date2 = new Date(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const target = date2.getFullYear() + "-" + (date2.getMonth()+1) + "-" + date2.getDate();
    $.ajax({
        type: "post",
        url: "../util/update_database.php",
        data:  {
            function: "timeFilter",
            curr: curr,
            target: target
        }
    }).done(res=>{
        document.title = "Next Seven Days";
        updatePanel(res);
        retrieveAllSelect("category");
        $('#bigTabLabel').text("Next Seven Days");
    }).fail(res=>{
        console.log(res.responseText);
    })
});

$('.modal').on('shown.bs.modal', function() {
       $("body.modal-open").removeAttr("style");
 });

$('#sm-categories').submit(function (e) {
    e.preventDefault();
    if($('input[name=list]:checked').length == 1) {
        let option = $('input[name=list]:checked').val();
         $.ajax({
            type: "post",
            url: "../util/update_database.php",
            data:  {
                function: "categoryFilter",
                idcategory: option
            }
        }).done(res=>{
            updatePanel(res);
            updateSelect(option);
            $('#select-list').modal('hide');
        }).fail(res=>{
            console.log(res.responseText);
        })
    } else {
        $('#select-list').modal('hide');
    }
});

$('#sm-categories .btn-outline-danger').on('click', function (e) {
    if($('input[name=list]:checked').length == 1) {
        let option = $('input[name=list]:checked').val();
        if(confirm("You are about to delete everything under this category. Are you sure about it?")){
           $.ajax({
               type: "post",
               url: "../util/update_database.php",
               data: {
                 function: "deleteCategory",
                 idcategory: option
               }
           }).done(res=>{
                location.reload();
           });
        }else{
            console.log("Not deleted"); 
        }
    } else {
        $('#select-list').modal('hide');
    }
});

$('.bottom div:nth-child(4)').click(function (e) {
    console.log(e);
    $("#sm-categories").css('visibility', 'hidden');
    $('#select-list .modal-header').outerHeight(56);
    $('#select-list').modal('show');
});

$('#select-list').on('shown.bs.modal', function (e) {
    console.log(e);
    let bottomHeight = $('#select-list .modal-content').height() - 56;
    $('#select-list .modal-body').outerHeight(bottomHeight);
    let formHeight = $('#select-list .modal-body').height();
    $("#sm-categories").outerHeight(formHeight);
    let listsHeight = formHeight - $("#list-options").next().height();
    $("#list-options").height(listsHeight);
    // $("#sm-categories").css('display', 'none');
    $("#sm-categories").css('visibility', 'visible');
    $("#sm-categories").fadeIn();
})
