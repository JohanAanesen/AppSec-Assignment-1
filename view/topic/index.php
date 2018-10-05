<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = false;

$topics = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $topics = $app->get_topicId($id);
}else{
    $topics = $app->get_topics();
}


echo $twig->render('topic.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
));