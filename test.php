<?php exit(0);

require_once 'model/Application.php';
require_once 'model/Logger.php';
require_once 'model/SessionManager.php';

$app = Application::get_instance();

SessionManager::session_start( 'app' );

if ( isset( $_GET['register'] ) && ! $app->is_logged_in() ) {
	if ( isset( $_POST['r_username'] ) ) {
		$username = $_POST['r_username'];
		$email = $_POST['r_email'];
		$password = $_POST['r_password'];

		if ( $app->register_user( $username, $email, $password ) ) {
			// TODO: Go to another page
			$app->redirect( 'test.php' );
		} else {
			// TODO: Go somewhere else
		}
	} else {
		$app->redirect( 'test.php' );
	}
}

if ( isset( $_GET['login'] ) && ! $app->is_logged_in() ) {
	if ( isset( $_POST['l_username'] ) ) {
		$username = $_POST['l_username'];
		$password = $_POST['l_password'];

		if ( $app->login_user( $username, $password ) ) {
			// TODO: Go to another page
			$app->redirect( 'test.php' );
		} else {
			// TODO: Go somewhere else
		}
	} else {
		$app->redirect( 'test.php' );
	}
}

if ( isset( $_GET['logout'] ) && $app->is_logged_in() ) {
	$app->logout_user();
	$app->redirect( 'test.php' );
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

<div class="container pt-3">

	<div class="row mb-3">
		<?php if ( SessionManager::get_flashdata( 'success_msg' ) ) : ?>
			<div class="col">
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success!</strong> <?php echo SessionManager::get_flashdata( 'success_msg' ); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<!-- /.alert alert-success -->
			</div>
			<!-- /.col -->
		<?php endif; ?>

		<?php if ( SessionManager::get_flashdata( 'warning_msg' ) ) : ?>
			<div class="col">
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<strong>Warning!</strong> <?php echo SessionManager::get_flashdata( 'warning_msg' ); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<!-- /.alert alert-warning -->
			</div>
			<!-- /.col -->
		<?php endif; ?>

		<?php if ( SessionManager::get_flashdata( 'error_msg' ) ) : ?>
			<div class="col">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Error!</strong> <?php echo SessionManager::get_flashdata( 'error_msg' ); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<!-- /.alert alert-danger -->
			</div>
			<!-- /.col -->
		<?php endif; ?>
	</div>
	<!-- /.row -->

	<div class="row mb-3">

		<?php if ( ! $app->is_logged_in() ) : ?>
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
								<label for="r_password_confirm">Confirm Password <span
											class="text-danger">*</span></label>
								<input type="password" name="r_password_confirm" id="r_password_confirm"
								       class="form-control" required>
								<!-- /.form-control -->
							</div>
							<!-- /.form-group -->
							<div class="form-group">
								<input type="submit" name="r_submit" id="r_submit" value="Sign up"
								       class="btn btn-secondary btn-block">
							</div>
							<!-- /.form-group -->
						</form>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-4 -->
		<?php endif; ?>

		<?php if ( ! $app->is_logged_in() ) : ?>
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
								<input type="submit" name="l_submit" id="l_submit" value="Sign in"
								       class="btn btn-secondary btn-block">
								<!-- /.btn btn-secondary -->
							</div>
							<!-- /.form-group -->
						</form>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-4 -->
		<?php endif; ?>

		<?php if ( $app->is_logged_in() ) : ?>
			<div class="col-4">
				<div class="card">
					<h5 class="card-header">
						Sign out
					</h5>
					<!-- /.card-header -->

					<div class="card-body">
						<a href="test.php?logout" class="btn btn-secondary btn-block">Sign out</a>
						<!-- /.btn btn-secondary -->
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-4 -->
		<?php endif; ?>

		<?php if ( $app->is_logged_in() ) : ?>
			<div class="col-4">
				<div class="card">
					<h5 class="card-header">
						Profile details
					</h5>
					<!-- /.card-header -->

					<div class="card-body p-0">
						<table class="table table-striped">
							<thead>
							<tr>
								<th>Key</th>
								<th>Value</th>
							</tr>
							</thead>

							<tbody>
							<?php foreach ( SessionManager::get_userdata() as $key => $value ) : ?>
								<tr>
									<td><?= $key; ?></td>
									<td><?= $value; ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<!-- /.table table-striped -->
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-4 -->
		<?php endif; ?>

	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col">
			<div class="jumbotron py-4">
				<h3>Session:</h3>
				<?php
				echo '<pre>';
				print_r( $_SESSION );
				echo '</pre>';
				?>
			</div>
			<!-- /.jumbotron -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->


</div>
<!-- /.container -->

<script src="assets/js/jquery-slim.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
