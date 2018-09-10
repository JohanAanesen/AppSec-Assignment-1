<?php

require_once __DIR__ . "/../../classes/Server.php";

$db     = Server::requireDatabase();
$twig   = Server::requireTwig();



echo $twig->render('home.html', array(
    'title' => 'home'
));


