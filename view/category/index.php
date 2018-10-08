<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();

$topics = null;
$category = null;
$data[] = null;
$counter = 0;

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $topics = $app->get_topicsWithCategory($id);
    $category = $app->get_category($id);

}else{
    $topics = $app->get_topics();
    $category["title"] = "All topics";
}

foreach ($topics as $topic) {
    $temp = $app->get_latestReplyFromTopic($topic["topicId"]);
    $topic += $temp;
    $data[$counter++] = $topic;
}

echo $twig->render('category.html', array(
    'title' => 'Horrible - Category',
    'loggedIn' => $loggedIn,
    'topics' => $data,
    'category' => $category,
));