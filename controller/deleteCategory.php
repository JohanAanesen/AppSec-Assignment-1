<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/9/2018
 * Time: 16:17
 */

require_once '../model/Application.php';
$app = Application::get_instance();

list( $catId, $userId ) = $app->requireParameterArray(
    'd_catId',
    'd_userId'
);

if($app->get_user_role($userId) == 'admin'){
    $app->delete_category( $catId );
}

$app->redirect( "./");
