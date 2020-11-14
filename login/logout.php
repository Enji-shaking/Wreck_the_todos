<?php
session_start();

if ( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] ||
        !isset($_SESSION['iduser']) || empty($_SESSION['iduser']) 
	) 
	{
        header('Location: login.php');
    }
else{
	session_destroy();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="../style.css">
</head>
<body>

	<div class="container">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Logout</h1>
			<div class="col-12">You are now logged out.</div>
			<div class="col-12 mt-3">You can safely exit or go to <a href="login.php">Log in</a> again.</div>

		</div> <!-- .row -->
	</div> <!-- .container -->

</body>
</html>