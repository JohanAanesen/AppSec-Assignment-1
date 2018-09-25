<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 10.09.2018
 * Time: 14.53
 */

require_once 'config.php';

/**
 * Class Application
 */
class Application {

	/**
	 * @var Database
	 */
	private $db;

	/**
	 * @var UserController
	 */
	private $userController;

	/**
	 * Application constructor.
	 */
	public function __construct() {
		$this->db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
		$this->userController = new UserController( $this->db->getDB(), 'user' );
	}

	/**
	 * @param string $path
	 */
	public function redirect( $path = '' ) {
		$path = (empty($path)) ? __FILE__ : $path;
		header('Location: ' . $path );
	}

	public function test() {
		return $this->userController->test();
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function login_user( $username, $password ) {
		return $this->userController->login( $username, $password );
	}

	/**
	 *
	 */
	public function logout_user() {
		SessionManager::set_flashdata( SessionManager::SUCCESS, 'Successfully logged out!' );
		Logger::write( sprintf( "User \"%s\" has successfully logged out.", SessionManager::get_userdata( 'user_info' )['username'] ), Logger::SUCCESS );
		SessionManager::delete_userdata();
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	public function register_user( $username, $email, $password ) {
		return $this->userController->create( $username, $email, $password );
	}

	public function get_users() {
		return $this->userController->read();
	}

	public function get_user_by_id( $id ) {
		return $this->userController->read_id( $id );
	}

	public function get_user_by_username( $username ) {
		return $this->userController->read_username( $username );
	}

	public function is_logged_in() {
		return !empty( SessionManager::get_userdata() );
	}

}