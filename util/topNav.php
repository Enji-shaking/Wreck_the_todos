

<div class="col-12 d-flex justify-content-end row">
	<h3 class="p-2"> Wreck the todos! </h3> 
    <h3 class="p-2"><i class="fas fa-check-circle"></i></h3> 
	<?php if(isset($_atDetail) && $_atDetail): ?>
		<div class="ml-auto p-2 ">Hello <?php echo $_SESSION["username"]; ?> !</div>
		<a class="p-2 " href="../homepage/homepage.php">Home</a>
		<a class="p-2 " href="../login/logout.php">Logout</a>
	<?php else: ?>
	<?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
		<div class=" ml-auto p-2 ">Hello <?php echo $_SESSION["username"]; ?> !</div>
		<a class="p-2 " href="../login/logout.php">Logout</a>
	<?php else: ?>
		<a class="ml-auto p-2 " href="../login/login.php">Login</a>
	<?php endif; ?>
	<?php endif; ?>
	
</div>

