<?php

require_once __DIR__ . "/../../model/Application.php";

$app = Application::get_instance();
$twig = $app->get_twig();

$loggedIn = $app->is_logged_in();

$user = SessionManager::get_userdata();

$admin = false;
if($app->get_user_role(SessionManager::get_userdata('userId')) == 'admin'){
    $admin = true;
}

$categories = $app->get_categories();
$data[] = null;
$counter = 0;

foreach ( $categories as $category ) {
	$temp = $app->get_latestTopicFromCategory( $category["categoryId"] );
	$category += $temp;
	$data[$counter++] = $category;
}


try {
	echo $twig->render( 'home.twig', array(
		'title' => 'Home',
		'loggedIn' => $loggedIn,
		'categoryTableRows' => $data,
        'user' => $user,
        'admin' => $admin,
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
