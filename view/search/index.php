<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();


if(!isset($_GET["query"])){
    header('Location: /');
}

$query = $_GET['query'];




echo $twig->render('search.html', array(
    'title' => 'Search',
    'loggedIn' => $loggedIn,
    'query' => $query,
));

