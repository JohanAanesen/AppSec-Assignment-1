<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:11
 */

require_once '../model/Application.php';
$app = Application::get_instance();

if ( ! $app->is_logged_in() ) {
	if ( isset( $_POST['l_username'] ) ) {
		$username = $_POST['l_username'];
		$password = $_POST['l_password'];

		$userRemember = isset( $_POST['l_remember'] );

		if ( $app->login_user( $username, $password ) ) {
			if ( $userRemember ) {
				// $app->remember_user(); //TODO: rememberMe function :)
			}

			$app->redirect( './' );
		} else {
			$app->redirect( './' );
		}
	} else {
		$app->redirect( './' );
	}
}