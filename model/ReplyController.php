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
			$stmt = $this->db->prepare( "SELECT * FROM $this->table
            /**
                                                  WHERE topicId = topic.topicId //Kanskje???
            **/                                   
            " );
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
		 * NAME                TYPE        KEY-TYPE        VARIABLE
		 * replyId            INT            PRIMARY KEY
		 * topicId            INT            FOREIGN KEY
		 * userId            INT            FOREIGN KEY
		 * content            TEXT
		 * timestamp        DATETIME                    date('Y-m-d H:i:s')
		 * editTimestamp    DATETIME                    NULL
		 */

		// TODO: Make create, read, update & delete functions for this class

		// NOTE: This class need to check if topic & user ID's exists aswell
	}

	public function create( $topicId, $userId, $content ) {
		try {
			// Set current date
			$timestamp = date( 'Y-m-d H:i:s' );

			// prepare SQL query and bind parameters
			$stmt = $this->db->prepare( "INSERT INTO $this->table SET topicId=:topicId, userId=:userId, content=:content, timestamp=:timestamp, editTimestamp=:timestamp2" );
			$stmt->bindParam( ':topicId', $topicId, PDO::PARAM_STR );
			$stmt->bindParam( ':userId', $userId, PDO::PARAM_INT );
			$stmt->bindParam( ':content', $content, PDO::PARAM_STR );
			$stmt->bindParam( ':timestamp', $timestamp, PDO::PARAM_STR );
			$stmt->bindParam( ':timestamp2', $timestamp, PDO::PARAM_STR );


			// Check if execution went through
			if ( $stmt->execute() ) {
				SessionManager::set_flashdata( 'success_msg', 'Reply successfully created!' );
				Logger::write( sprintf( 'New Reply created in (topic %s)', $topicId ), Logger::SUCCESS );
				return true;
			} else {
				print_r( $stmt->errorInfo() );
				SessionManager::set_flashdata( 'error_msg', 'Could not create reply!' );
				Logger::write( sprintf( 'Attempt on creating topic failed: (IP: %s, topicId: %s)', $_SERVER['REMOTE_ADDR'], $topicId ), Logger::WARNING );
				return false;
			}
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function read_repliesFromTopic( $topicId ) {
		try {
			$stmt = $this->db->prepare( "SELECT reply.userId, reply.topicId, reply.content, reply.timestamp, reply.editTimestamp, reply.replyId, user.username 
                                                    FROM $this->table
                                                    INNER JOIN topic ON reply.topicId = topic.topicId
                                                    INNER JOIN user ON reply.userId = user.userId
                                                    WHERE reply.topicId=:id
                                                    ORDER BY reply.timestamp ASC" );

			$stmt->bindParam( ':id', $topicId, PDO::PARAM_STR );

			$stmt->execute();

			return $stmt->fetchAll( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_latestReplyFromTopic( $topicId ) {
		try {
			$stmt = $this->db->prepare( "SELECT reply.replyId, reply.userId AS replyUserId, reply.content AS replyTitle, reply.timestamp AS replyStamp, user.username AS replyUser
                                                    FROM $this->table
                                                    INNER JOIN user ON user.userId = reply.userId
                                                    WHERE reply.topicId=:topID
                                                    ORDER BY reply.timestamp DESC 
                                                    LIMIT 1" );
			$stmt->bindParam( ':topID', $topicId, PDO::PARAM_INT );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return ( ! empty( $result ) ) ? $result[0] : $result;

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

    public function deleteRepliesFromTopic( $topicId ) {
	    $success = false;
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare( "DELETE FROM $this->table WHERE topicId=:topicId" );

            $stmt->bindParam( ':topicId', $topicId, PDO::PARAM_INT );

            if($stmt->execute()){
                $success = true;
            }
        } catch ( PDOException $e ) {
            SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
            Logger::write( $e->getMessage(), Logger::ERROR );
            $this->db->rollBack();
            return false;
        }

        $this->db->commit();

        return $success;
    }
}