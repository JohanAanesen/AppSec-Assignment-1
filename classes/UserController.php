<?php

class UserController extends ITable {

	public function __construct( PDO $db, $table ) {
		parent::__construct( $db, $table );
	}

	public function test() {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table" );
			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_CLASS, "User" );

			foreach ( $result as $u ) {
				unset( $u->password );
			}

			return $result;
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	/**
	 * @param string $username			- Account username
	 * @param string $password			- Account hashed password
	 * @return bool						- True if all is good, else return false.
	 */
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
					Logger::write( sprintf( 'Attempt on logging in to locked account: %s', $username ), Logger::ERROR );
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
					SessionManager::set_userdata( $user );
					Logger::write( sprintf( "User \"%s\" has successfully logged in.", $username ), Logger::SUCCESS );
					return true;

				} else {
					// Increment failed login attempts
					$user['loginAttempts']++;
					$stmt = $this->db->prepare("UPDATE $this->table SET loginAttempts=:loginAttempts WHERE username=:username");
					$stmt->bindParam( ':loginAttempts', $user['loginAttempts'], PDO::PARAM_INT );
					$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
					$stmt->execute();

					// Set flash and log data with message
					SessionManager::set_flashdata( 'warning_msg', 'Wrong credentials.' );
					Logger::write( sprintf( "Password verification failed for user: \"%s\"", $username ), Logger::WARNING );
					return false;
				}

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Wrong credentials.' );
				Logger::write( sprintf( "Could not find user \"%s\" in database.", $username ), Logger::ERROR );
				return false;
			}
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function create( $username, $email, $password ) {
		try {
			// Check if username is already in use
			if ( $this->read_username( $username ) ) {
				SessionManager::set_flashdata( 'warning_msg', 'Username is already in use!' );
				Logger::write( sprintf( 'Account creation, username already taken: "%s"', $username ), Logger::WARNING );
				return false;
			}

			// Check if email is already in use
			if ( $this->read_email( $email ) ) {
				SessionManager::set_flashdata( 'warning_msg', 'Email is already in use!' );
				Logger::write( sprintf( 'Account creation, email already taken: "%s"', $email ), Logger::WARNING );
				return false;
			}

			// Check if username is at least 3 characters long
			if ( sizeof( $username ) < 3 ) {
				SessionManager::set_flashdata( 'warning_msg', 'Username needs to be at least 3 character long!' );
				return false;
			}

			// Set current date
			$date = date('Y-m-d');

			// Encrypt password


			// Prepare SQL query and bind parameters
			$stmt = $this->db->prepare( "INSERT INTO $this->table SET username=:username, email=:email, password=:password, dateJoined=:dateJoined, loginAttempts=0" );
			$stmt->bindParam( ':username', $username, PDO::PARAM_STR );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
			$stmt->bindParam( ':password', $password, PDO::PARAM_STR );
			$stmt->bindParam( ':dateJoined', $date, PDO::PARAM_STR );

			// Check if execution went through
			if ( $stmt->execute() ) {
				$stmt = $this->db->prepare( "INSERT INTO userrole SET userId=:userId, role=1" );
				$stmt->bindParam( ":userId", $this->db->lastInsertId(), PDO::PARAM_INT );
				$stmt->execute();

				SessionManager::set_flashdata( 'success_msg', 'Account successfully created!' );
				Logger::write( sprintf( 'New account created: (%s, %s)', $username, $email ), Logger::SUCCESS );
				return true;

			} else {
				SessionManager::set_flashdata( 'error_msg', 'Could not create account!' );
				Logger::write( sprintf( 'Attempt on creating account failed: (IP: %s, Username: %s, Email: %s)', $_SERVER['REMOTE_ADDR'], $username, $email ), Logger::WARNING );
				return false;

			}

		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function read() {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table" );

			$stmt->execute();

			return $stmt->fetchAll( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_id( $id ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE userId=:uid" );
			$stmt->bindParam( ':uid', $id, PDO::PARAM_INT );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return (!empty( $result )) ? $result[0] : $result;
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_username( $username ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE username=:username" );
			$stmt->bindParam( ':username', $username, PDO::PARAM_STR );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return (!empty( $result )) ? $result[0] : $result;
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return array();
		}
	}

	public function read_email( $email ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE email=:email" );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			return (!empty( $result )) ? $result[0] : $result;
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function read_user_role( $userId ) {
		try {
			$stmt = $this->db->prepare( "SELECT role FROM userrole WHERE userId=:userId" );
			$stmt->bindParam( ':userId', $userId, PDO::PARAM_INT );

			$stmt->execute();

			return $stmt->fetch( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function update( $args ) {
		try {
			// TODO: Make code for this
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

	public function delete( $args ) {
		try {
			// TODO: Make code for this
		} catch ( PDOException $e ) {
			SessionManager::set_flashdata( 'error_msg', $e->getMessage() );
			Logger::write( $e->getMessage(), Logger::ERROR );
			return false;
		}
	}

}