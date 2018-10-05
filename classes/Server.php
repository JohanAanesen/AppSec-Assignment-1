<?php
define( "DB_HOSTNAME", "localhost" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "DB_DATABASE", "forum" );
define( "DB_CHARSET", "utf8" );

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/ITable.php";

class Server{

    private static $db = null;
    private $session = null;

    private function __construct() {
        $this->db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
    }


    public static function requireDatabase() {
        return Server::$db;
    }

    public static function requireTwig() {
        $path = __DIR__ . "/../twig";
        $loader = new Twig_Loader_Filesystem($path);
        return new Twig_Environment($loader, array(
            //    'cache' => './compilation_cache',
        ));
    }
}