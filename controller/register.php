<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:12
 */

require_once '../model/Application.php';
$app = Application::get_instance();

if ( !$app->is_logged_in() ) {
    if ( isset($_POST['r_username']) ) {
        $username = $_POST['r_username'];
        $email = $_POST['r_email'];
        $password = $_POST['r_password'];

        if ( $app->register_user( $username, $email, $password ) ) {
            // TODO: Go to another page
            $app->redirect('/' );
        } else {
            // TODO: Go somewhere else
            $app->redirect('/');
        }
    } else {
        $app->redirect('/');
    }
}