<?php

require_once "config.php";

if (isset($_GET['register'])) {
	if ( $userController->create(array(
		'username'  => $_POST['username'],
		'password'  => password_hash( $_POST['password'], PASSWORD_DEFAULT ),
		'email'     => $_POST['email'],
	)) ) {
		echo "User created!<br>";

	} else {
		echo "Could not create user!<br>";

	}
}

if (isset($_GET['login'])) {
	if ( $userController->login(array( 'username'  => $_POST['username'], 'password'  => $_POST['password'] ))) {
		echo "Logged in!<br>";

	} else {
		echo "Wrong credentials!<br>";

	}
}

if (isset($_GET['s']) && !empty($_GET['s'])) {
	$search = htmlspecialchars( $_GET['s'] );

	$result = $userController->read(array('username' => $search));

	echo "<pre>";
	print_r($result);
	echo "</pre>";
}

?>

<form action="index.php" method="get">
	<h1>Search</h1>
	<input type="search" name="s" placeholder="Search...">
	<input type="submit" name="submit" value="Search">
</form>

<form action="index.php?register" method="post">
	<h1>Register</h1>
	<label for="username">Username</label>
	<input type="text" id="username" name="username"><br>
	<label for="password">Password</label>
	<input type="password" id="password" name="password"><br>
	<label for="email">Email</label>
	<input type="email" id="email" name="email"><br>
	<input type="submit" id="submit" name="submit" value="Register">
</form>

<form action="index.php?login" method="post">
	<h1>Login</h1>
	<label for="username">Username</label>
	<input type="text" id="username" name="username"><br>
	<label for="password">Password</label>
	<input type="password" id="password" name="password"><br>
	<input type="submit" id="submit" name="submit" value="Login">
</form>
