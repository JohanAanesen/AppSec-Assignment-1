<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:11
 */

require_once '../model/Application.php';
$app = Application::get_instance();


list( $title, $userId ) = $app->requireParameterArray(
    'c_title',
    'c_user'
);

if($app->get_user_role($userId) == 'admin'){
    $app->create_newCategory( $title );
}

$app->redirect( "./");
