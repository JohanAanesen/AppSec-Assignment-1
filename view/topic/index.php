<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();


$topics = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $topics = $app->get_topicId($id);
}else{
    $topics = $app->get_topics();
}


echo $twig->render('topic.html', array(
    'title' => 'Horrible - Topic',
    'loggedIn' => $loggedIn,
));