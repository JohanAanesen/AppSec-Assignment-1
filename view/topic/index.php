<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();


$topics = null;
$replies = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $topic = $app->get_topicId($id);
    $replies = $app->get_replies($id);
}else{
    $app->redirect("category");
}


echo $twig->render('topic.html', array(
    'title' => 'Horrible - Topic',
    'loggedIn' => $loggedIn,
    'topic' => $topic,
    'replies' => $replies,
));