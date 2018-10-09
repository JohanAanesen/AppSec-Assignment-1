<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();

$createTopic = false;
$topics = null;
$category = null;
$data[] = null;
$counter = 0;

if ( isset( $_GET['id'] ) ) {
	$id = $_GET['id'];
	$topics = $app->get_topicsWithCategory( $id );
	$category = $app->get_category( $id );

	if ( $loggedIn ) {
		$createTopic = true;
	}
} else {
	$topics = $app->get_topics();
	$category["title"] = "All topics";
}

foreach ( $topics as $topic ) {
	$temp = $app->get_latestReplyFromTopic( $topic["topicId"] );
	$topic += $temp;
	$data[$counter++] = $topic;
}

$categoryList = $app->get_categories();
$user = SessionManager::get_userdata();

try {
	echo $twig->render( 'category.twig', array(
		'title' => 'Horrible - Category',
		'loggedIn' => $loggedIn,
		'topics' => $data,
		'category' => $category,
		'createTopic' => $createTopic,
		'categoryList' => $categoryList,
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