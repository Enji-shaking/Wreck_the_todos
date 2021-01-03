<?php 
    require '../util/config.php';
    if ( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] ||
        !isset($_SESSION['iduser']) || empty($_SESSION['iduser']) 
    ) {
        header('Location: ../login/login.php');
    	exit();
    }
    $mysqli = connect();
    if($mysqli->connect_errno){
        echo $mysqli->connect_error;
        exit();
    }

    $sqlIncomplete = "select tasks.duedate, tasks.description, tasks.title, tasks.complete, categories.category, tasks.idtask, categories.idcategory from tasks 
    left join categories
        on tasks.idcategory = categories.idcategory
    where tasks.iduser = " . $_SESSION["iduser"] . " and complete = 0;";

    $resultIncomplete = $mysqli->query($sqlIncomplete);
    if(!$resultIncomplete){
        echo $mysqli->error;
        exit();
    }
    $numIncomplete = $resultIncomplete->num_rows;

    $sqlComplete = "select tasks.duedate, tasks.description, tasks.title, tasks.complete, tasks.idtask, categories.category, categories.idcategory from tasks 
    left join categories
        on tasks.idcategory = categories.idcategory
    where tasks.iduser = " . $_SESSION["iduser"] . " and complete = 1;
    ";
    $resultComplete = $mysqli->query($sqlComplete);
    if(!$resultComplete){
        echo $mysqli->error;
        exit();
    }
    $numComplete = $resultComplete->num_rows;


    $sqlCategory = "select * from categories where iduser = " . $_SESSION['iduser'];

    $results_catagories = $mysqli->query($sqlCategory);
    if(!$results_catagories){
        echo $mysqli->error;
        exit();
    }

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../favicon.ico">
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="../resource/bootstrap.min.css">
    <!-- font awesome CSS -->
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
        integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="../resource/fontawesome/css/all.css">
    
    <!-- google fonts -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="../resource/googleFont.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="leftNav.css">
    <link rel="stylesheet" href="bottomNav.css">
    <link rel="stylesheet" href="../style.css">
    <title>All Tasks</title>
</head>

<body>
    <?php include '../util/topNav.php'; ?>

    <div class="container-fluid canvas">
        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header mt-1 mr-1">
                        <h5 class="modal-title" id="detailTitle">Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm">
                            <input type="hidden" name="idtask" value="" id="updateTaskId"/>
                            <!-- title -->
                            <div class="form-group row">
                                <label for="updateTitle" class="col-sm-3 col-form-label text-sm-right">
                                    Title: <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="updateTitle" name="title" value="">
                                </div>
                                <p class="alert alert-dark w-75 m-auto mt-3 text-center d-none" role="alert" id="alertNoTitleUpdate">
                                    Must enter a title!
                                </p>
                                
                            </div>
                            <!-- duedate -->
                            <div class="form-group row">
                                <label for="updateduedate" class="col-sm-3 col-form-label text-sm-right">
                                    Duedate: <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control mt-2" name="duedate" id="updateduedate">
                                </div>
                            </div>
                            <!-- description  -->
                            <div class="form-group row">
                                <label for="updateDescription" class="col-sm-3 col-form-label text-sm-right">
                                    Description: <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <textarea name="description" rows="5" placeholder="Put specific description here..." id="updateDescription" class="form-control"></textarea>
                                </div>
                            </div>
                            <!-- category -->
                            <div class="form-group row">
                                <label for="updateDescription" class="col-sm-3 col-form-label text-sm-right">
                                    Category: <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="updateCategories" name="idcategory">

                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row align-items-end">
                                <div class="flex-grow-1 invisible"></div>
                                <button type="button" class="col-4 btn btn-secondary mr-1" data-dismiss="modal">Close</button>
                                <button type="submit" class="col-4 btn btn-primary mr-3">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mx-1">
            <div class="leftGroup col-3">
                <?php include "leftNav.php" ?>
            </div>
            <div class="middleGroup col-12 col-md-9">
                <div class="row align-items-end col-12 mr-0 pr-0">
                    <div class="col-5">
                        <h4 id="bigTabLabel" class="mb-0">All Tasks</h4>
                    </div>
                    <div class="count flex-grow-1">
                        <span> <span id="numCompleted"><?php echo $numComplete ?></span> completed, <span id="numIncompleted"><?php echo $numIncomplete ?></span> todo</span>
                    </div>
                    <i class="fas fa-plus-square fa-3x plusToggle"></i>
                </div>

                <div class="row mt-2">
                    <form id="inputTask" class="col-12">
                        <div class="form-row form-group">
                            <div class="col-8">
                                <input id="title" class="form-control" type="text" name="title" placeholder="Quickly add an item: Item Title" />
                            </div>
                            <div class="col-4 pl-1">
                                <select class="form-control" name = "idcategory" id="category" >
                                
                                </select>
                            </div>
                        </div>
                        <div class="alert alert-dark d-none w-100 p-3" role="alert" id="alertNoTitle">
                            Must enter a title!
                        </div>
                        <p class="alert alert-dark w-75 m-auto mt-3 text-center d-none" role="alert" id="alertNoCategory">
                            Must create a category first!
                        </p>
                        <div class="form-row mt-1">
                            <div class="col">
                                <input type="date" class="form-control col-12" name="duedate" id="duedate">
                            </div>
                        </div>
                        <div id="description" class="align-items-end mt-2" >
                            <textarea class="form-control mr-1" name="description" rows="5" cols="45" placeholder="Put specific description here" id="descriptionTextArea"></textarea>
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>
                    </form>
                    <form id="sm-add-categories" class="col-12 mt-2">
                        <div class="form-row">
                            <div class="col-9 form-group my-auto">
                                <input type="text" name="sm-new-category" id="sm-new-category" placeholder="Create a category" class="form-control">
                            </div>
                            <button class="btn btn-warning btn-block col-3 my-auto" type="submit">Create</button>
                        </div>
                        <h6 class="alert text-danger font-italic m-0 pb-0 font-size-12 d-none">
                            * Must enter a name for the category!
                        </h6>
                    </form>
                </div>

                <h5 class="mt-3">Incompleted tasks</h5>
                <div class="d-flex flex-column bd-highlight mb-3 mt-3" id="incompletePanel">
                    <?php while($row = $resultIncomplete->fetch_assoc()): ?>
                        <div>
                            <div class="taskitem" data-toggle="modal" data-target="#modal">
                                <input type="hidden" class="idcategory" value="<?php echo $row['idcategory'];?> " >
                                <div class="p-2 d-flex flex-row align-items-center">
                                    <span class="d-none idtask"><?php echo $row["idtask"];?></span> 
                                    <i class="far fa-square check"></i>
                                    <span class="titletask flex-grow-1"><?php echo $row["title"];?></span>
                                    <a type="button" class="btn btn-sm btn-outline-info delete"> <i class="fas fa-eraser"></i> </a>
                                </div>
                                <div class="category d-flex justify-content-end">
                                    <span class="categorytask flex-grow-1"><?php echo $row["category"]; //fixed ?> </span> 
                                    <span class="ml-auto duedate">
                                        <?php echo $row["duedate"]; ?>
                                    </span>
                                </div>
                                <span class="taskdescription d-none">
                                    <?php echo $row["description"]; ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <h5>Completed tasks</h5>
                <div class="d-flex flex-column bd-highlight mb-3" id="completedPanel">
                    <?php while($row = $resultComplete->fetch_assoc()): ?>
                        <div>
                            <div class="taskitem" data-toggle="modal" data-target="#modal">
                                <input type="hidden" class="idcategory" value="<?php echo $row['idcategory'];?> " >
                                <div class="p-2 completed d-flex flex-row align-items-center">
                                    <span class="d-none idtask"><?php echo $row["idtask"]; ?></span> 
                                    <i class="fas fa-check-square check"></i> 
                                        <span class="titletask flex-grow-1"><?php echo $row["title"]; ?></span>
                                    <a type="button" class="btn btn-sm btn-outline-info delete"> <i class="fas fa-eraser"></i> </a>
                                </div>
                                <div class="category d-flex justify-content-end">
                                    <span class="categorytask flex-grow-1"><?php echo $row["category"]; //fixed ?> </span> 
                                    <span class="ml-auto duedate">
                                        <?php echo $row["duedate"]; ?>
                                    </span>
                                </div>
                            </div>
                            <span class="taskdescription d-none">
                                <?php echo $row["description"]; ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </div>
    <?php include "bottomNav.php" ?>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->
    <script src="../resource/jquery-3.5.1.js"></script>
    <script src="../resource/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script> -->
    <script src="../resource/loading.js"></script>
    
    <script src="main.js"></script>
    <script src="leftNav.js"></script>
    <script src="bottomNav.js"></script>
<!-- https://gasparesganga.com/labs/jquery-loading-overlay/ -->
</body>

</html>