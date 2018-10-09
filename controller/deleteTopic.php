<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/9/2018
 * Time: 13:42
 */

require_once '../model/Application.php';
$app = Application::get_instance();

list( $topicId, $userId ) = $app->requireParameterArray(
    'topicId',
    'userId'
);


if($app->delete_topic($topicId, $userId)){
    //TODO: set flashdata success
    $app->redirect( "./category");
}else{
    //TODO: set flashdata error
    $app->redirect("./topic?id=".$topicId);
}

