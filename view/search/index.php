<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();


if ( ! isset( $_GET["query"] ) ) {
	header( 'Location: /' );
}

$query = $_GET['query'];


try {
	echo $twig->render( 'search.twig', array(
		'title' => 'Search',
		'loggedIn' => $loggedIn,
		'query' => $query,
	) );
} catch ( Twig_Error_Loader $e ) {
	echo $e->getMessage();
	exit( $e->getCode() );
} catch ( Twig_Error_Runtime $e ) {
	echo $e->getMessage();
	exit( $e->getCode() );
} catch ( Twig_Error_Syntax $e ) {
	echo $e->getMessage();
	exit( $e->getCode() );
}

