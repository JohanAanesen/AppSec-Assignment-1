<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:11
 */

require_once '../model/Application.php';
$app = Application::get_instance();

if ( $app->is_logged_in() ) {
    $app->logout_user();
    $app->redirect('./' );
}