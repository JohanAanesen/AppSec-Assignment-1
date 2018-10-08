<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = false;
$loggedIn = $app->is_logged_in();


$categories = $app->get_categories();
$data[] = null;
$counter = 0;

foreach ($categories as $category) {
    $temp = $app->get_latestTopicFromCategory($category["categoryId"]);
    $category += $temp;
    $data[$counter++] = $category;
}


echo $twig->render('home.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
    'categoryTableRows' => $data,
));
