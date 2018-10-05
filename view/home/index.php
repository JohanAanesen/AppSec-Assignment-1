<?php

require_once __DIR__ . "/../../config.php";


$db     = Server::requireDatabase();
$twig   = Server::requireTwig();

$app = Application::get_instance();

$loggedIn = false;

$test = null;
$test[0] = new test();
$test[1] = new test();
$test[2] = new test();
$test[3] = new test();


echo $twig->render('home.html', array(
    'title' => 'Home',
    'loggedIn' => $loggedIn,
    'forumTableRows' => $test,
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