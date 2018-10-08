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
     * @var ReplyController
     */
	private $replyController;

	/**
	 * Application constructor.
	 */
	protected function __construct() {
		$this->db = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_CHARSET );
		$this->userController = new UserController( $this->db->getDB(), 'user' );
		$this->categoryController= new CategoryController( $this->db->getDB(),'category');
		$this->topicController = new TopicController($this->db->getDB(), 'topic');
		$this->replyController = new ReplyController($this->db->getDB(), 'reply');
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

    /**
     * @param $param
     * @return null
     */
    public function requireParameter($param) {
        $resultParam = null;
        if (isset($_GET[$param])) {
            $resultParam = $_GET[$param];
        }
        else if (isset($_POST[$param]) ) {
            $resultParam = $_POST[$param];
        }
        else {
            SessionManager::set_flashdata(SessionManager::ERROR, 'Something went wrong');
            $this->redirect("/");
        }
        return $resultParam;
    }

    /**
     * @param mixed ...$paramArray
     * @return array
     */
    public function requireParameterArray(...$paramArray) {
        $result = array();
        foreach ($paramArray as $param) {
            array_push($result, $this->requireParameter($param));
        }
        return $result;
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
        $path = ROOTPATH . "/assets/twig/";
        $loader = new Twig_Loader_Filesystem($path);
        return new Twig_Environment($loader, array(
            //    'cache' => './compilation_cache',
        ));
    }

    public function get_categories(){
	    return $this->categoryController->read();
    }

    public function get_category($catId){
        return $this->categoryController->read_categoryFromId($catId);
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

    public function get_latestTopicFromCategory($catId){
	    return $this->topicController->read_latestTopicFromCategory($catId);
    }

    public function get_replies($topicId){
	    return $this->replyController->read_repliesFromTopic($topicId);
    }

    public function get_latestReplyFromTopic($topicId){
	    return $this->replyController->read_latestReplyFromTopic($topicId);
    }

    public function create_newReply($topicId, $userId, $content, $timestamp){
	    //TODO: $this->replyController->create($topicId, $userId, $content, $timestamp);
    }

    public function create_newTopic($categoryId, $userId, $title, $content){
	    $temp = $this->topicController->create($categoryId, $userId, $title, $content);
        if($temp){
            $this->redirect('/');
        }else{
            //$this->redirect('FUCKYOU');
        }
    }

}