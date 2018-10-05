<?php

require_once __DIR__ . "/../../classes/Server.php";

$db     = Server::requireDatabase();
$twig   = Server::requireTwig();

$loggedIn = false;




echo $twig->render('topic.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
));