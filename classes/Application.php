<?php

require_once 'config.php';

/**
 * Class Application
 */
class Application {

	/**
	 * Singelton variable
	 * @var
	 */
	protected static $instance;

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
	protected function __construct() {
		$this->db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
		$this->userController = new UserController( $this->db->getDB(), 'user' );
	}

	static public function get_instance() {
		if ( !self::has_instance() ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	static protected function has_instance() {
		return self::$instance instanceof self;
	}

	/**
	 * @param string $path
	 */
	public function redirect( $path = '' ) {
		$path = (empty($path)) ? __FILE__ : $path;
		header('Location: ' . $path );
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

	public function get_user_role( $userId ) {
		return ($this->userController->read_user_role( $userId ) == 1) ? 'user' : 'admin';
	}

}