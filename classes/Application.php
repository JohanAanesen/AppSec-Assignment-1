<?php

require_once __DIR__ . '/../config.php';

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
     * @var CategoryController
     */
	private $categoryController;

    /**
     * @var TopicController
     */
	private $topicController;

	/**
	 * Application constructor.
	 */
	protected function __construct() {
		$this->db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
		$this->userController = new UserController( $this->db->getDB(), 'user' );
		$this->categoryController= new CategoryController( $this->db->getDB(),'category');
		$this->topicController = new TopicController($this->db->getDB(), 'topic');
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
		Logger::write( sprintf( "User \"%s\" has successfully logged out.", SessionManager::get_userdata( 'username' ) ), Logger::SUCCESS );
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

    public function get_twig() {
        $path = ROOTPATH . "/twig/";
        $loader = new Twig_Loader_Filesystem($path);
        return new Twig_Environment($loader, array(
            //    'cache' => './compilation_cache',
        ));
    }

    public function get_categories(){
	    return $this->categoryController->read();
    }


    public function get_topicId($id){
	    return $this->topicController->read_topicId($id);
    }

    public function get_topics(){
	    return $this->topicController->read_topic();
    }

    public function get_topicsWithCategory($catId){
	    return $this->topicController->read_topicsFromCategory($catId);
    }

}