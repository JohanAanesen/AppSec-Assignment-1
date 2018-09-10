<?php

require_once 'classes/Application.php';
require_once 'classes/Logger.php';
require_once 'classes/SessionManager.php';

$app = new Application();

SessionManager::session_start( 'app', 60*24 );

if ( isset( $_GET['register'] ) ) {
	if ( isset($_POST['r_username']) ) {
		$username = $_POST['r_username'];
		$email = $_POST['r_email'];
		$password = password_hash( $_POST['r_password'], PASSWORD_DEFAULT );

		if ( $app->register_user( $username, $email, $password ) ) {
			// TODO: Go to another page
		} else {
			// TODO: Go somewhere else
		}
	} else {
		$app->redirect('test.php');
	}
}

if ( isset( $_GET['login'] ) ) {
	if ( isset($_POST['l_username']) ) {
		$username = $_POST['l_username'];
		$password = $_POST['l_password'];

		if ( $app->login_user( $username, $password ) ) {
			// TODO: Go to another page
		} else {
			// TODO: Go somewhere else
		}
	} else {
		$app->redirect('test.php');
	}
}


?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

<div class="container">

	<div class="row py-5 bg-light">

		<div class="col-4">
			<div class="card">
				<h5 class="card-header">
					Sign up
				</h5>
				<!-- /.card-header -->
				<div class="card-body">
					<form action="test.php?register" method="post">
						<div class="form-group">
							<label for="r_username">Username <span class="text-danger">*</span></label>
							<input type="text" name="r_username" id="r_username" class="form-control" required>
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<label for="r_email">Email <span class="text-danger">*</span></label>
							<input type="text" name="r_email" id="r_email" class="form-control" required>
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<label for="r_password">Password <span class="text-danger">*</span></label>
							<input type="password" name="r_password" id="r_password" class="form-control" required>
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<label for="r_password_confirm">Confirm Password <span class="text-danger">*</span></label>
							<input type="password" name="r_password_confirm" id="r_password_confirm" class="form-control" required>
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<input type="submit" name="r_submit" id="r_submit" value="Sign up" class="btn btn-secondary btn-block">
						</div>
						<!-- /.form-group -->
					</form>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col-3 -->

		<div class="col-4">
			<div class="card">
				<h5 class="card-header">
					Sign in
				</h5>
				<!-- /.card-header -->
				<div class="card-body">
					<form action="test.php?login" method="post">
						<div class="form-group">
							<label for="l_username">Username</label>
							<input type="text" name="l_username" id="l_username" class="form-control">
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<label for="l_password">Password</label>
							<input type="password" name="l_password" id="l_password" class="form-control">
							<!-- /.form-control -->
						</div>
						<!-- /.form-group -->
						<div class="form-group">
							<input type="submit" name="l_submit" id="l_submit" value="Sign in" class="btn btn-secondary btn-block">
							<!-- /.btn btn-secondary -->
						</div>
						<!-- /.form-group -->
					</form>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->

	</div>
	<!-- /.row -->

	<?php
	echo '<pre>';
	print_r( $_SESSION );
	echo '</pre>';
	?>

	<?php if ( SessionManager::get_flashdata( 'success_msg' ) ) : ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Success!</strong> <?php echo SessionManager::get_flashdata( 'success_msg' ); ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<!-- /.alert alert-success -->
	<?php endif; ?>

	<?php if ( SessionManager::get_flashdata( 'warning_msg' ) ) : ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<strong>Warning!</strong> <?php echo SessionManager::get_flashdata( 'warning_msg' ); ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<!-- /.alert alert-warning -->
	<?php endif; ?>

	<?php if ( SessionManager::get_flashdata( 'error_msg' ) ) : ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Error!</strong> <?php echo SessionManager::get_flashdata( 'error_msg' ); ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<!-- /.alert alert-danger -->
	<?php endif; ?>


</div>
<!-- /.container -->

<script src="assets/js/jquery-slim.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
