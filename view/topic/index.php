<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();

$user = SessionManager::get_userdata();

$topics = null;
$replies = null;
if ( isset( $_GET['id'] ) ) {
	$id = $_GET['id'];
	$topic = $app->get_topicId( $id );
	$replies = $app->get_replies( $id );
} else {
	$app->redirect( "category" );
}

try {
	echo $twig->render( 'topic.twig', array(
		'title' => 'Horrible - Topic',
		'loggedIn' => $loggedIn,
		'topic' => $topic,
		'replies' => $replies,
		'user' => $user,
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