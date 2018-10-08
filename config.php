<?php

define( "DB_HOSTNAME", "localhost" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "DB_DATABASE", "forum" );
define( "DB_CHARSET", "utf8" );
define( "ROOTPATH", __DIR__);

require_once ROOTPATH . "/vendor/autoload.php";
require_once ROOTPATH . "/classes/Database.php";
require_once ROOTPATH . "/classes/ITable.php";
require_once ROOTPATH . "/classes/UserController.php";
require_once ROOTPATH . "/classes/CategoryController.php";
require_once ROOTPATH . "/classes/TopicController.php";
require_once ROOTPATH . "/classes/ReplyController.php";
require_once ROOTPATH . "/classes/SessionManager.php";
require_once ROOTPATH . "/classes/Logger.php";

SessionManager::session_start( 'app' );

/*$db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
$session = new Session();
$userController = new User( $db->getDB(), "user" );
$forumController = new Forum( $db->getDB(), 'forum' );
$postController = new Post( $db->getDB(), 'post' );
*/
