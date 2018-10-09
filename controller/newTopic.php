<?php
/**
 * Created by IntelliJ IDEA.
 * User: Johan Aanesen
 * Date: 10/8/2018
 * Time: 16:12
 */

require_once '../model/Application.php';
$app = Application::get_instance();

list( $category, $title, $content, $user ) = $app->requireParameterArray(
	'category',
	'title',
	'content',
	'userId'
);

$app->create_newTopic( $category, $title, $content, SessionManager::get_userdata( "userId" ) );

$app->redirect( "./category?id=" . $category );