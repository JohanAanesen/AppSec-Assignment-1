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
    $app->redirect( "./category");
}else{
    $app->redirect("./topic?id=".$topicId);
}

