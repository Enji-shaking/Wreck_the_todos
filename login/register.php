<?php
    require "../util/config.php";
    if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] ){
        header("Location: ../main/main.php");
    }

    if( isset($_POST["username"]) && !empty($_POST["username"]) && 
    isset($_POST["password"]) && !empty($_POST["password"])
    ){
        $mysqli = connect();
        if($mysqli->connect_errno) {
            echo $mysqli->connect_error;
            exit();
        }
        $statement = $mysqli->prepare("SELECT * FROM users WHERE username = ?;");
        $statement->bind_param("s",$username);
        $username = $_POST['username'];
        $statement->execute();
        $results = $statement->get_result();
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        // var_dump($results);
        if($results->num_rows > 0){
            $error = "yes";
        }else{
            $statement1 = $mysqli->prepare("INSERT into users(username, pass) VALUES(?,?);");
            $statement1->bind_param("ss",$username1, $pass1);
            $username1 = $_POST['username'];
            $pass1 = hash("sha256", $_POST['password']);
            $statement1->execute();
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["logged_in"] = true;
            $_SESSION["iduser"] = $mysqli->insert_id;
            echo '<script alert("Registered successfully. Please log in")</script>';
            header("Location: ../main/main.php");
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
        integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>

<?php
include "../util/topNav.php"
?>


<div>
    <div class="container w-80 d-flex align-items-center justify-content-center">
      <div class="wrapper">
        <h1 class="row justify-content-center">
          <span class="align-baseline"> Wreck the todos! </span> 
          <span class="align-top"><i class="fas fa-check-circle"></i></span> 
        </h1>
        <h3>Registering...</h3>
        <form action="#" id="myform" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">User Name</label>
                <input type="text" class="form-control" name="username" id="username">
            </div>
            <div class="form-group">
                <label for="password1">Password</label>
                <input name="password", type="password" class="form-control" id="password1">
            </div>
            <div class="form-group">
                <label for="password2">Confirm Password</label>
                <input name="", type="password" class="form-control" id="password2">
            </div>

            <div class="font-italic text-danger col-sm-9 ml-sm-auto d-none" id="error1">
                Must enter a username
            </div>
            <div class="font-italic text-danger col-sm-9 ml-sm-auto d-none" id="error2">
                Passwords don't match
            </div>
            <div class="font-italic text-danger col-sm-9 ml-sm-auto d-none" id="error3">
                Must enter a password
            </div>
            <?php if( isset($error) && !empty($error) ): ?>
                <div class="font-italic text-danger col-sm-9 ml-sm-auto" id="error3">
                    Username not unique!
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
</script>
<script src="register.js"></script>
    
</body>
</html>