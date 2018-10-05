<?php

require_once __DIR__ . "/../../classes/Server.php";

$db     = Server::requireDatabase();
$twig   = Server::requireTwig();


$loggedIn = false;


if(!isset($_GET["query"])){
    header('Location: /');
}

$query = $_GET['query'];




echo $twig->render('search.html', array(
    'title' => 'Search',
    'loggedIn' => $loggedIn,
    'query' => $query,
));

