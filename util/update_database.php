<?php
    require "config.php";
    $mysqli = connect();
    if($mysqli->connect_errno){
        die($mysqli->connect_error);
    }

    if($_POST['function'] == "completeTask"){
        $statement = $mysqli->prepare("update tasks set complete = ? where idtask = ?");
        $statement->bind_param("ii", $_POST['completedChangeTo'], $_POST['idtask']);
        $executed = $statement->execute();
        if(!$executed){
            die($mysqli->error);
        }
    }else if($_POST['function'] == "updateTask"){
        $statement = $mysqli->prepare("update tasks set title = ?, idcategory=?, description=?, duedate=? where idtask = ?");
        $statement->bind_param("sissi", $_POST['title'], $_POST['idcategory'], $_POST['description'], $_POST['duedate'], $_POST['idtask']);
        $executed = $statement->execute();
        if(!$executed){
            die($mysqli->error);
        }

        $sql = "select * from tasks join categories
        on tasks.idcategory = categories.idcategory where tasks.idtask = " . $_POST["idtask"] . ";";
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_assoc();
        echo json_encode($result);
    }else if($_POST['function'] == "deleteTask"){
        $statement = $mysqli->prepare("delete from tasks where idtask = ?");
        echo $_POST['idtask'];
        $statement->bind_param("i",  $_POST['idtask']);
        $executed = $statement->execute();
        if(!$executed){
            die($mysqli->error);
        }
    }else if($_POST['function'] == 'insertTask'){
            if(isset($_POST["title"]) && !empty($_POST["title"]) ){
            $stmt = $mysqli->prepare("insert into tasks(iduser, idcategory, description, title, complete, duedate)
            values (?,?,?,?,?,?); ");
            $stmt->bind_param("iissis", $iduser, $idcategory, $description, $title, $complete, $duedate);
            $iduser = $_SESSION["iduser"];
            $title = $_POST["title"];
            $idcategory = $_POST["idcategory"];
            $duedate = $_POST["duedate"];
            $description = $_POST["description"];
            $complete = 0;
            $exe = $stmt->execute();
            if(!$exe){
                die($mysqli->error);
                exit();
            }
            $id = $mysqli->insert_id;
            $sql = "select * from tasks left join categories
            on tasks.idcategory = categories.idcategory where idtask = " . $id .";";
            $result = $mysqli->query($sql);
            if(!$result){
                die($mysqli->error);
                exit();
            }
            $row = $result->fetch_assoc();
            echo json_encode($row);
        }else{
            die("NOT fulfilled all the input fields");
        }
    }else if($_POST['function'] == 'getTask'){
        if(empty($_POST['idtask'])){
            header('HTTP/1.1 400 empty idtask field');
            die();
        }
        $sql = "select * from tasks join categories
        on tasks.idcategory = categories.idcategory where tasks.idtask = " . $_POST["idtask"] . ";";
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_assoc();
        echo json_encode($result);
    }else if($_POST['function'] == 'insertCategory'){
        if(isset($_POST["category"]) && !empty($_POST["category"])){
            $stmt = $mysqli->prepare("insert into categories(category, iduser)
            values (?, ?) ");
            $stmt->bind_param("si", $_POST["category"], $_SESSION["iduser"]);
            $exe = $stmt->execute();
            if(!$exe){
                header('HTTP/1.1 500 SQL executing error');
                die($mysqli->error);
            }
            $id = $mysqli->insert_id;
            echo $id;
        }else{
            echo "no category name set";
        }
    }else if($_POST['function'] == 'categoryFilter'){
        if(empty($_POST['idcategory'])){
            header('HTTP/1.1 400 empty idcategory field');
            die();
        }
        $sql = "select * from tasks join categories
            on tasks.idcategory = categories.idcategory where tasks.idcategory = " . $_POST["idcategory"] . ";";
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    }else if($_POST['function'] == 'getCategory'){
        if(empty($_POST['idcategory'])){
            header('HTTP/1.1 400 empty idcategory field');
            die();
        }
        $sql = "select * from categories where idcategory = " . $_POST['idcategory'] . ';';
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_assoc();
        echo json_encode($result);
    }else if($_POST['function'] == 'deleteCategory'){
        if(empty($_POST['idcategory'])){
            header('HTTP/1.1 400 empty idcategory field');
            die();
        }

        $statement = $mysqli->prepare("delete from tasks where idcategory = ?");
        $statement->bind_param("i",  $_POST['idcategory']);
        $executed = $statement->execute();
        if(!$executed){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $statement = $mysqli->prepare("delete from categories where idcategory = ?");
        $statement->bind_param("i",  $_POST['idcategory']);
        $executed = $statement->execute();
        if(!$executed){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        echo "success";
    }else if($_POST['function'] == 'todayFilter'){
        if(empty($_POST['time'])){
            header('HTTP/1.1 400 empty time field');
            die();
        }
        $sql = "select * from tasks left join categories on tasks.idcategory = categories.idcategory where tasks.duedate = '" . $_POST['time'] . "' AND tasks.iduser = " . $_SESSION['iduser'] . ";";
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    }else if($_POST['function'] == 'timeFilter'){
        if(empty($_POST['curr']) || empty($_POST['target'])){
            header('HTTP/1.1 400 empty time field');
            die();
        }
        $sql = "select * from tasks left join categories on tasks.idcategory = categories.idcategory where tasks.duedate >= '" . $_POST['curr'] . "' AND tasks.duedate <= '" . $_POST['target'] . "' AND tasks.iduser = " . $_SESSION['iduser'] . ";";
        $result = $mysqli->query($sql);
        if(!$result){
            header('HTTP/1.1 500 SQL executing error');
            die($mysqli->error);
        }
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    }else if($_POST['function'] == 'getAllCategories'){
        $sqlCategory = "select * from categories where iduser = " . $_SESSION['iduser'];
        $result = $mysqli->query($sqlCategory);
        if(!$result){
            echo $mysqli->error;
            exit();
        }
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($result);
    }else{
        echo "non suitable function";
    }
    
    