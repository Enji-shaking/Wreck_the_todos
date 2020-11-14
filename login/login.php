<?php 
  require "../util/config.php";
  if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] 
      && isset($_SESSION["iduser"]) && $_SESSION["iduser"]
  ){
    header("Location: ../main/main.php");
  }

  if ( isset($_POST['username']) && isset($_POST['password']) ) {
    if ( empty($_POST['username']) || empty($_POST['password']) ) {
      $error = "Please enter username and password.";
    }else{
      $mysqli = connect();
      if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
      }
      $passwordInput = hash("sha256", $_POST['password']);
      $statement = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND pass = ?;");
      $statement->bind_param("ss",$username, $passwordInput);
      $username = $_POST['username'];
      $statement->execute();
      $results = $statement->get_result();
      if(!$results) {
        echo $mysqli->error;
        exit();
      }

      if($results->num_rows > 0) {
        //one result back, means username/pw combination is correct
        //sessions to remember
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["logged_in"] = true;
        $row = $results->fetch_assoc();
        $_SESSION["iduser"] = $row["iduser"];
        //redirect
        header("Location: ../main/main.php");
      }
      else {
        $error = "Invalid username or password.";
      }
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../style.css">
    <title>Hello, world!</title>
  </head>

  <body>
    <div class="container w-80 d-flex align-items-center justify-content-center">
      <div class="wrapper">
        <div id="log-in-form">
          <h2 class="row justify-content-center">
            <span class="align-baseline"> Wreck the todos! </span> 
            <span class="align-top"><i class="fas fa-check-circle"></i></span> 
          </h2>
          <form action="#" method="POST">
            <div class="form-group">
              <label for="exampleInputEmail1">User Name</label>
              <input type="text" class="form-control" name="username">
          
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input name="password", type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="row mb-3">
              <div class="font-italic text-danger col-sm-9 ml-sm-auto">
                <!-- Show errors here. -->
                <?php
                if ( isset($error) && !empty($error) ) {
                  echo $error;
                }
                ?>
              </div>
            </div>
            <a href="register.php" class="btn btn-info" role="button">Register</a>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>

      
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>


    </script>
  </body>
</html>