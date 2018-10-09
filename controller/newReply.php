<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:12
 */

require_once '../model/Application.php';
$app = Application::get_instance();



list($topic, $user, $content) = $app->requireParameterArray(
    'topicId',
    'n_user',
    'n_content'
);

$timestamp = date('Y-m-d H:i:s');

$app->create_newReply($topic, $user, $content, $timestamp);

$app->redirect("./topic?id=".$topic);
