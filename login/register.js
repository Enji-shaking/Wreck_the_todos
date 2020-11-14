console.log("ccc");
$('#myform').submit(function (e) { 
    console.log("123");
    let pass1 = $("#password1").val().trim();
    let pass2 = $("#password2").val().trim();
    let username = $("#username").val().trim();
    // console.log($("#username"));
    if(username===""){
        e.preventDefault();
        $("#error1").removeClass("d-none");
    }else{
        $("#error1").addClass("d-none");
    }
    if(pass1 !== pass2){
        e.preventDefault();
        $("#error2").removeClass("d-none");
    }else{
        $("#error2").addClass("d-none");
    }
    if(pass1 === ""){
        e.preventDefault();
        $("#error3").removeClass("d-none");
    }else{
        $("#error3").addClass("d-none");
    }
});