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
    'n_topic',
    'n_user',
    'n_content'
);

$app->create_newReply($topic, $user, $content);

$app->redirect("./topic?id=".$topic);
