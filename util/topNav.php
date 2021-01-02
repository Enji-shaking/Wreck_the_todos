

<div class="col-12 d-flex justify-content-end row">
	<h3 class="p-2 brand-name mr-auto"> Wreck the todos! <i class="fas fa-check-circle fa-sm"></i></h3>
	<?php if(isset($_atDetail) && $_atDetail): ?>
		<div class="p-2 username">Hello <?php echo $_SESSION["username"]; ?> !</div>
		<a class="p-2" href="../homepage/homepage.php">Home</a>
		<a class="p-2" href="../login/logout.php">Logout</a>
	<?php else: ?>
	<?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
		<div class="my-auto mr-2 username">Hello <?php echo $_SESSION["username"]; ?> !</div>
		<a class="my-auto px-3 py-2 btn btn-dark" href="../login/logout.php">Logout</a>
	<?php else: ?>
		<a class="my-auto px-sm-2 px-md-3 py-2 btn btn-dark" href="../login/login.php">Login</a>
	<?php endif; ?>
	<?php endif; ?>
	
</div>

