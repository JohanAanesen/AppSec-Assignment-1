<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/9/2018
 * Time: 14:30
 */

require_once '../model/Application.php';
$app = Application::get_instance();

list( $topicId, $userId, $replyId ) = $app->requireParameterArray(
    'topicId',
    'userId',
    'replyId'
);

$isOwner = false;

$user = SessionManager::get_userdata('userId');
$userRole = $app->get_user_role($user);

if($user = $userId || $userRole = 'admin'){
    $isOwner = true;
}
if($isOwner){
    if($app->delete_reply($topicId, $userId, $replyId)){
        //TODO: set flashdata success
        $app->redirect("./topic?id=".$topicId);
    }else{
        //TODO: set flashdata error
        $app->redirect("./topic?id=".$topicId);
    }
}else{
    //TODO: set flashdata error
    $app->redirect("./topic?id=".$topicId);
}
