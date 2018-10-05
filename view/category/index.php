<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = false;

$topics = null;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $topics = $app->get_topicsWithCategory($id);
}else{
    $topics = $app->get_topics();
}



echo $twig->render('category.html', array(
    'title' => 'Horrible - Category',
    'loggedIn' => $loggedIn,
    'topics' => $topics,
));