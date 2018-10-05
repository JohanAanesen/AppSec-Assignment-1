<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();


$categories = $app->get_categories();


echo $twig->render('home.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
    'categoryTableRows' => $categories,
));
