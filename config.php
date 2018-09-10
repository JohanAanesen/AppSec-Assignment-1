<?php

define( "DB_HOSTNAME", "localhost" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "DB_DATABASE", "forum" );
define( "DB_CHARSET", "utf8" );

require_once "classes/Database.php";
require_once "classes/Session.php";
require_once "classes/ITable.php";
require_once "classes/User.php";
require_once "classes/Forum.php";
require_once "classes/Post.php";

$db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
$session = new Session();
$userController = new User( $db->getDB(), "user" );
$forumController = new Forum( $db->getDB(), 'forum' );
$postController = new Post( $db->getDB(), 'post' );
