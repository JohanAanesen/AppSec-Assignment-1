<?php

class TopicController extends ITable {

	public function __construct( PDO $db, string $table ) {
		parent::__construct( $db, $table );
	}

	public function create($topicId, $categoryId, $userId, $title, $content, $dateWritten, $editTimestamp) {
		try {
			// check if topic exists
			if ( $this->read_topicId( $topicId)) {
				SessionManager::set_flashdata( 'warning_msg', 'topicId is already taken!' );
				Logger::write( sprintf(  'Topic creation, topic already taken: "%s"', $topicId ), Logger::WARNING );
				return false;
			}
			if ( $this->read_categoryId ($categoryId)){
				SessionManager::set_flashdata( 'warning_msg', 'Category is already taken!' );
				Logger::write( sprintf( 'Topic creation, topic already taken: "%s"', $categoryId ), Logger::WARNING );
				return false;
			}

			// Set current date
			$dateWritten = date('Y-m-d H:i:s');

			// prepare SQL queary and bind parameters
			$stmt = $this->db->prepare( "INSERT INTO $this->table SET topicId=:topicId, categoryId=:categoryId, userId=:userId, title=:title, content:content, dateWritten:dateWritten, editTimestamp:editTimestamp" );
			$stmt->bindParam( ':topicId', $topicId, PDO::PARAM_STR );
			$stmt->bindParam( ':categoryId', $categoryId, PDO::PARAM_STR );
			$stmt->bindParam( ':userId', $userId, PDO::PARAM_STR );
			$stmt->bindParam( ':title', $title, PDO::PARAM_STR );
			$stmt->bindParam( ':content', $content, PDO::PARAM_STR );
			$stmt->bindParam( ':dateWritten', $dateWritten, PDO::PARAM_STR );
			$stmt->bindParam( ':editTimestamp', $editTimestamp, PDO::PARAM_STR );

			// Check if execution went through
			if ( $stmt->execute() ) {
				$stmt = $this->db->prepare( "INSERT INTO topic SET topicId=:topicId, role=1" );
				$stmt->bindParam( ":topicID", $this->db->lastInsertId(), PDO::PARAM_INT );
				$stmt->execute();

				SessionManager::set_flashdata( 'success_msg', 'Topic successfully created!' );
				Logger::write( sprintf( 'New topic created: (%s, %s)', $topicId, $categoryId ), Logger::SUCCESS );
				return true;

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not create topic!' );
				Logger::write( sprintf( 'Attempt on creating topic failed: (IP: %s, topicId: %s, categoryId: %s)', $_SERVER['REMOTE_ADDR'], $topicId, $categoryId ), Logger::WARNING );
				return false;
			}
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function read_topic () {
		try {
			$stmt = $this->db->prepare( "SELECT topic.topicId, topic.categoryId, topic.userId AS topicUserId, topic.title AS topicTitle, topic.content AS topicContent, user.username AS topicUser, COUNT(reply.topicId LIKE topic.topicId) AS replies
                                                    FROM $this->table
                                                    INNER JOIN user ON user.userId = topic.userId
                                                    LEFT JOIN reply ON topic.topicId = reply.topicId
                                                    GROUP BY topic.topicId" );

			$stmt->execute();

			return $stmt->fetchAll( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_topicId( $topicId ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table 
                                                  INNER JOIN user ON user.userId = topic.userId
                                                  INNER JOIN category ON topic.categoryId = category.categoryId
                                                  WHERE topicId=:topicId" );
			$stmt->bindParam( ':topicId', $topicId, PDO::PARAM_INT );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return (!empty( $result )) ? $result[0] : $result;
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

    public function read_latestTopicFromCategory( $categoryID ) {
        try {
            $stmt = $this->db->prepare( "SELECT topic.topicId, topic.userId, topic.title AS topicTitle, topic.timestamp AS topicTimestamp, user.username AS topicUser
                                                    FROM $this->table
                                                    INNER JOIN user ON user.userId = topic.userId
                                                    WHERE topic.categoryId=:catID
                                                    ORDER BY topic.timestamp DESC 
                                                    LIMIT 1" );
            $stmt->bindParam( ':catID', $categoryID, PDO::PARAM_INT );

            $stmt->execute();

            $result = $stmt->fetchAll( PDO::FETCH_ASSOC );

            return (!empty( $result )) ? $result[0] : $result;

        } catch ( PDOException $e ) {
            SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
            Logger::write( $e->getMessage(), Logger::ERROR );
            return array();
        }
    }

    public function read_topicsFromCategory( $categoryID ) {
        try {
            $stmt = $this->db->prepare( "SELECT topic.topicId, topic.categoryId, topic.userId AS topicUserId, topic.title AS topicTitle, topic.content AS topicContent, user.username AS topicUser, COUNT(reply.topicId LIKE topic.topicId) AS replies
                                                    FROM $this->table
                                                    INNER JOIN user ON user.userId = topic.userId
                                                    LEFT JOIN reply ON topic.topicId = reply.topicId
                                                    WHERE topic.categoryId=:catID
                                                    GROUP BY topic.topicId" );
            $stmt->bindParam( ':catID', $categoryID, PDO::PARAM_INT );

            $stmt->execute();

            return $stmt->fetchAll( PDO::FETCH_ASSOC );

        } catch ( PDOException $e ) {
            SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
            Logger::write( $e->getMessage(), Logger::ERROR );
            return array();
        }
    }
/*	public function update( $args ) {
		try {
			// Make code for this
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}*/

	public function delete( $topicId ) {
		try {
			$stmt = $this->db->prepare( "DELETE FROM $this->table WHERE topicId=:topicId");

			return $stmt->execute();
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	/**
	 * DATABASE TABLE:
	 *
	 * NAME				TYPE		KEY-TYPE		VARIABLE
	 * topicId			INT			PRIMARY KEY
	 * categoryId		INT			FOREIGN KEY
	 * userId			INT			FOREIGN KEY
	 * title			VARCHAR
	 * content			TEXT
	 * timestamp		TIMESTAMP					date('Y-m-d H:i:s')
	 * editTimestamp	TIMESTAMP					NULL
	 */

	// TODO: Make create, read, update, delete functions for topics

	// NOTE: This class need to check if category and user ID's exists

}
