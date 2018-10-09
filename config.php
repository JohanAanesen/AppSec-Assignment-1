<?php

define( "DB_HOSTNAME", "localhost" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "DB_DATABASE", "forum" );
define( "DB_CHARSET", "utf8" );
define( "ROOTPATH", __DIR__ );

require_once ROOTPATH . "/vendor/autoload.php";
require_once ROOTPATH . "/model/Database.php";
require_once ROOTPATH . "/model/ITable.php";
require_once ROOTPATH . "/model/UserController.php";
require_once ROOTPATH . "/model/CategoryController.php";
require_once ROOTPATH . "/model/TopicController.php";
require_once ROOTPATH . "/model/ReplyController.php";
require_once ROOTPATH . "/model/SessionManager.php";
require_once ROOTPATH . "/model/Logger.php";

SessionManager::session_start( 'app' );

/*$db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
$session = new Session();
$userController = new User( $db->getDB(), "user" );
$forumController = new Forum( $db->getDB(), 'forum' );
$postController = new Post( $db->getDB(), 'post' );
*/
