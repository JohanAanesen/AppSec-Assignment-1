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

		$session->set_userdata('user', $userController->read(array('username' => $_POST['username']))[0]);

	} else {
		echo "Wrong credentials!<br>";
		$_SESSION['loggedIn'] = true;

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
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>

<form action="index.php" method="get">
	<h1>Search</h1>
	<input type="search" name="s" placeholder="Search...">
	<input type="submit" name="submit" value="Search">
</form>

<form action="index.php?register" method="post">
	<h1>Register</h1>
	<label for="r_username">Username</label>
	<input type="text" id="r_username" name="username"><br>
	<label for="r_password">Password</label>
	<input type="password" id="r_password" name="password"><br>
	<label for="r_email">Email</label>
	<input type="email" id="r_email" name="email"><br>
	<input type="submit" id="r_submit" name="submit" value="Register">
</form>

<form action="index.php?login" method="post">
	<h1>Login</h1>
	<label for="l_username">Username</label>
	<input type="text" id="l_username" name="username"><br>
	<label for="l_password">Password</label>
	<input type="password" id="l_password" name="password"><br>
	<input type="submit" id="l_submit" name="submit" value="Login">
</form>

<hr>

<?php

if ( $session->get_userdata('user' ) ) {
	echo '<pre>';
	print_r( $session->get_userdata('user') );
	echo '</pre>';
}
?>

</body>
</html>