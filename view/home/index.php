<?php

require_once __DIR__ . "/../../classes/Server.php";

$db     = Server::requireDatabase();
$twig   = Server::requireTwig();



$test = null;
$test[0] = new test();
$test[1] = new test();
$test[2] = new test();
$test[3] = new test();


echo $twig->render('home.html', array(
    'title' => 'Home',
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
        $this->topics = "test";
        $this->posts = "test";
        $this->lastPostTitle = "test";
        $this->lastPostAuthor = "test";
        $this->lastPostTimestamp = "test";
    }
}