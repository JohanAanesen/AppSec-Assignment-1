<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 25.09.2018
 * Time: 11.26
 */


/**
 * Class ReplyController
 */
class ReplyController extends ITable {

	public function __construct( PDO $db, string $table ) {
		parent::__construct( $db, $table );
	}

    public function test() {
        try {
            $stmt = $this->db->prepare( "SELECT * FROM $this->table" );
            $stmt->execute();

            $result = $stmt->fetchAll( PDO::FETCH_CLASS, "replyId, topicId, userId, content, timestamp" );

            foreach ( $result as $u ) {
                unset( $u->password );
            }

            return $result;

        } catch ( PDOException $e ) {
            SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
            Logger::write( $e->getMessage(), Logger::ERROR );
            return false;
        }

	/**
	 * DATABASE TABLE:
	 *
	 * NAME				TYPE		KEY-TYPE		VARIABLE
	 * replyId			INT			PRIMARY KEY
	 * topicId			INT			FOREIGN KEY
	 * userId			INT			FOREIGN KEY
	 * content			TEXT
	 * timestamp		DATETIME					date('Y-m-d H:i:s')
	 * editTimestamp	DATETIME					NULL
	 */

	// TODO: Make create, read, update & delete functions for this class

	// NOTE: This class need to check if topic & user ID's exists aswell

}