<?php

define( "DB_HOSTNAME", "localhost" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "DB_DATABASE", "forum" );
define( "DB_CHARSET", "utf8" );
define( "ROOTPATH", __DIR__);

//require_once __DIR__ . "/classes/Server.php";
require_once ROOTPATH . "/vendor/autoload.php";
require_once ROOTPATH . "/classes/Database.php";
require_once ROOTPATH . "/classes/ITable.php";
require_once ROOTPATH . "/classes/UserController.php";
require_once ROOTPATH . "/classes/CategoryController.php";
require_once ROOTPATH . "/classes/SessionManager.php";
require_once ROOTPATH . "/classes/Logger.php";

/*$db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
$session = new Session();
$userController = new User( $db->getDB(), "user" );
$forumController = new Forum( $db->getDB(), 'forum' );
$postController = new Post( $db->getDB(), 'post' );
*/
