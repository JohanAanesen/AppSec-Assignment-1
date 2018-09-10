<?php

class UserController extends ITable {

	public function __construct( PDO $db, $table ) {
		parent::__construct( $db, $table );
	}

	public function login( $username, $password ) {
		try {
			// Prepare SQL statement, and bind parameter to query
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE username=:username" );
			$stmt->bindParam( ':username', $username, PDO::PARAM_STR );

			// Execute SQL query and fetch data
			$stmt->execute();
			$user = $stmt->fetch( PDO::FETCH_ASSOC );

			// Check if we retrieved anything from the database
			if ( !empty( $user ) ) {

				// Check if user has too many login attempts
				if ( $user['loginAttempts'] >= 5 ) {
					// Prompt to the user that the account has been locked.
					SessionManager::set_flashdata( 'error_msg', 'Account is locked. Too many failed login attempts. Contact an admin to unlock account.' );
					return false;
				}

				// Verify if correct password
				if ( password_verify( $password, $user['password'] ) ) {
					// Reset login attempts to 0
					$stmt = $this->db->prepare( "UPDATE $this->table SET loginAttempts=0 WHERE username=:username" );
					$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
					$stmt->execute();

					// Unset data that doesn't have anything to do with the session, and save to session and flash data
					unset( $user['password'] );
					unset( $user['loginAttempts'] );
					SessionManager::set_flashdata( 'success_msg', 'Login successful!' );
					SessionManager::set_userdata( 'user_info', $user );
					return true;

				} else {
					// Increment failed login attempts
					$user['loginAttempts']++;
					$stmt = $this->db->prepare("UPDATE $this->table SET loginAttempts=:loginAttempts WHERE username=:username");
					$stmt->bindParam( ':loginAttempts', $user['loginAttempts'], PDO::PARAM_INT );
					$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
					$stmt->execute();

					// Set flash data with message
					SessionManager::set_flashdata( 'warning_msg', 'Wrong credentials.' );
					return false;
				}

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Wrong credentials.' );
				return false;
			}
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			return false;
		}
	}

	public function create( $username, $email, $password ) {
		try {
			// Set current date
			$date = date('Y-m-d');

			// Prepare SQL query and bind parameters
			$stmt = $this->db->prepare( "INSERT INTO $this->table SET username=:username, email=:email, password=:password, dateJoined=:dateJoined, loginAttempts=0" );
			$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
			$stmt->bindParam( ':password', $password, PDO::PARAM_STR );
			$stmt->bindParam( ':dateJoined', $date, PDO::PARAM_STR );

			// Check if execution went through
			if ( $stmt->execute() ) {
				SessionManager::set_flashdata( 'success_msg', 'Account successfully created!' );
				return true;
			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not create account!' );
				return false;
			}
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			return false;
		}
	}

	public function read( $args ) {
		try {

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			return false;
		}
	}

	public function update( $args ) {
		try {

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			return false;
		}
	}

	public function delete( $args ) {
		try {

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			return false;
		}
	}

}