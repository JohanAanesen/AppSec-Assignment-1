<?php

require_once __DIR__ . "/../../classes/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = false;


$categories = $app->get_categories();


echo $twig->render('home.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
    'forumTableRows' => $categories,
));


class test{
    public $title = null;
    public $description = null;
    public $topics = null;
    public $posts = null;
    public $lastPostTitle = null;
    public $lastPostAuthor = null;
    public $lastPostTimestamp = null;
    public function __construct(){
        $this->title = "Johan er flink ass";
        $this->description = "test";
        $this->topics = "2148";
        $this->posts = "test";
        $this->lastPostTitle = "test";
        $this->lastPostAuthor = "test";
        $this->lastPostTimestamp = "test";
    }
}