<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 25.09.2018
 * Time: 12.51
 */

class CategoryController extends ITable {

	public function __construct( PDO $db, $table ) {
		parent::__construct( $db, $table );
	}

	/**
	 * DATABASE TABLE
	 *
	 * NAME
	 * categoryID        INT            PRIMARY KEY
	 * title            VARCHAR
	 */

	public function create( $title ) {
		try {
			if ( strlen( $title ) < 3 ) {
				SessionManager::set_flashdata( 'error_msg', "Input too short" );
				Logger::write( sprintf( 'Create Category, Input too short: "%s"', $title ) );

				return false;

			}
			//Sjekker om kategorien finnes
			if ( $this->read_category( $title ) ) {
				SessionManager::set_flashdata( 'error_msg', "Category already exists" );
				Logger::write( sprintf( 'Category creation, category already exists: "%s"', $title ) );
				return false;
			}
			$stmt = $this->db->prepare( "INSERT INTO $this->table SET title=:title" );

			$stmt->bindParam( 'title', $title, PDO::PARAM_STR );

			if ( $stmt->execute() ) {
				SessionManager::set_flashdata( 'success_msg', 'Category successfully created!' );
				Logger::write( sprintf( 'New category created: "%s"', $title ), Logger::SUCCESS );
				return true;

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not create category!' );
				Logger::write( sprintf( 'Attempt on creating category failed: (IP: %s, Category: %s)', $_SERVER['REMOTE_ADDR'], $title ), Logger::WARNING );
				return false;
			}

		} catch ( PDOException $e ) {

			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}


	public function read() {
		try {
			$stmt = $this->db->prepare( "SELECT category.categoryId, category.title, COUNT(topic.categoryId LIKE category.categoryId) AS topics
                                                    FROM $this->table 
                                                    LEFT JOIN topic ON category.categoryId = topic.categoryId
                                                    LEFT JOIN user ON topic.userId = user.userId
                                                    GROUP BY category.title" );

			if ( $stmt->execute() ) {

				SessionManager::set_flashdata( 'success_msg', 'Categories successfully loaded!' );
				Logger::write( sprintf( 'Categories read', Logger::SUCCESS ) );

				return $stmt->fetchAll( PDO::FETCH_ASSOC );

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not read categories!' );
				Logger::write( sprintf( 'Attempt on reading categories failed: (IP: %s', $_SERVER['REMOTE_ADDR'] ), Logger::WARNING );
				return false;
			}

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}


	//Sjekker om gitt kategori finnes
	public function read_category( $title ) {
		try {
			$stmt = $this->db->prepare( "SELECT * from $this->table WHERE title=:title" );

			$stmt->bindParam( ':title', $title, PDO::PARAM_STR );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return ( ! empty( $result ) ) ? $result[0] : $result;

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_categoryFromId( $id ) {
		try {
			$stmt = $this->db->prepare( "SELECT * from $this->table WHERE categoryId=:id" );

			$stmt->bindParam( ':id', $id, PDO::PARAM_STR );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return ( ! empty( $result ) ) ? $result[0] : $result;

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_topicsFromCategory( $categoryID ) {
		try {
			$stmt = $this->db->prepare( "SELECT category.categoryId, category.title
                                                    FROM $this->table" );

			$stmt->execute();

			return $stmt->fetchAll( PDO::FETCH_ASSOC );

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function update( $title, $newTitle ) {
		try {
			if ( ! $this->read_category( $title ) ) {
				SessionManager::set_flashdata( 'error_msg', "Category doesnt exist" );
				Logger::write( sprintf( 'Category update, category doesnt exist: "%s"', $title ) );
				return false;
			}

			if ( $this->read_category( $newTitle ) ) {
				SessionManager::set_flashdata( 'error_msg', "Category already exist" );
				Logger::write( sprintf( 'Category update, category already exist: "%s"', $newTitle ) );
				return false;
			}

			$stmt = $this->db->prepare( "UPDATE $this->table SET title=:newTitle WHERE title=:title" );

			$stmt->bindParam( ':title', $title, PDO::PARAM_STR );

			$stmt->bindParam( ':newTitle', $newTitle, PDO::PARAM_STR );

			if ( $stmt->execute() ) {

				SessionManager::set_flashdata( 'success_msg', 'Category successfully updated!' );
				Logger::write( sprintf( 'Category updated: "%s" changed to "%s"', $title, $newTitle ), Logger::SUCCESS );
				return true;

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not update account!' );
				Logger::write( sprintf( 'Attempt on updating category failed: Category: %s)', $title ), Logger::WARNING );
				return false;
			}


		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function delete( $catId ) {
	    $this->db->beginTransaction();
		try {
            $sql = 'SELECT topic.topicId FROM topic WHERE categoryId=:catId';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam( ':catId', $catId, PDO::PARAM_STR );
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row) {
                $sql = 'DELETE FROM reply WHERE topicId=:topicId';
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam( ':topicId', $row['topicId'], PDO::PARAM_STR );
                $stmt->execute();
            }

            $sql = 'DELETE FROM topic WHERE categoryId=:catId';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam( ':catId', $catId, PDO::PARAM_STR );
            $stmt->execute();

			$stmt = $this->db->prepare( "DELETE FROM $this->table WHERE categoryId=:catId" );
			$stmt->bindParam( ':catId', $catId, PDO::PARAM_STR );

			if ( $stmt->execute() ) {
				SessionManager::set_flashdata( 'success_msg', 'Category successfully deleted!' );
				Logger::write( sprintf( 'Category deleted.' ), Logger::SUCCESS );
				$this->db->commit();
				return true;

			} else {

				SessionManager::set_flashdata( 'error_msg', 'Could not delete category!' );
				Logger::write( sprintf( 'Attempt on deleting category failed: (IP: %s, Category: %s)', $_SERVER['REMOTE_ADDR'], $title ), Logger::WARNING );
                $this->db->rollBack();
				return false;
			}


		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			$this->db->rollBack();
			return false;
		}
	}
}
