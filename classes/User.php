<?php

/**
 * Class User
 */
class User extends ITable {

	/**
	 * User constructor.
	 * @param $db PDO
	 * @param $table string
	 */
	public function __construct( $db, $table ) {
		parent::__construct( $db, $table );
	}

	/**
	 * @param $username
	 * @return bool
	 */
	private function usernameExists( $username ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE username=:username" );
			$stmt->bindParam( ":username", $username, PDO::PARAM_STR );

			$stmt->execute();
			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			// DB fetch got an entry
			if (sizeof($result) > 0) {
				echo "ERROR: USERNAME TAKEN<br>";
				return true;
			}

			return false;
		} catch ( PDOException $e ) {
			echo $e->getMessage();
			return true;
		}
	}

	/**
	 * @param $username string
	 * @return bool
	 */
	private function validateUsername( $username ) {
		if ( !isset( $username ) && empty( $username ) || !preg_match( "/^[\w]{3,20}$/i", $username ) ) {
			echo "ERROR USERNAME<br>";
			return false;
		}

		return true;
	}

	/**
	 * @param $password string
	 * @return bool
	 */
	private function validatePassword( $password ) {
		if ( !isset( $password ) && empty( $password ) ) {

			echo "ERROR PASSWORD<br>";
			return false;
		}

		if (strlen( $password ) < 8) {
			echo "ERROR: SHORT PASSWORD<br>";
			return false;
		}

		return true;
	}

	private function validateEmail( $email ) {
		return filter_var( filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
	}

	private function successfulLogin($username) {
		try {
			$stmt = $this->db->prepare("UPDATE $this->table SET loginAttempts=0 WHERE username=:username");
			$stmt->bindParam(":username", $username, PDO::PARAM_STR);

			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	private function failedLogin( $username, $attempts ) {
		try {
			$newAttempts = $attempts + 1;
			$stmt = $this->db->prepare("UPDATE $this->table SET loginAttempts=:loginAttempts WHERE username=:username");
			$stmt->bindParam(":loginAttempts", $newAttempts, PDO::PARAM_INT);
			$stmt->bindParam(":username", $username, PDO::PARAM_STR);

			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function login( $args ) {
		try {
			$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE username=:username");
			$stmt->bindParam( ':username', $args['username'], PDO::PARAM_STR);

			$stmt->execute();
			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

			if ( sizeof( $result ) == 1 ) {
				$user = $result[0];
				$attempts = $user['loginAttempts'];

				if ($attempts >= 3) {
					return false;
				}

				if ( password_verify( $args['password'], $user['password'] ) ) {
					// TODO: Set login attempts to zero
					$this->successfulLogin($user['username']);
					return true;
				} else {
					// TODO: Increment login attempts
					echo "WRONG PASSWORD<br>";
					$this->failedLogin($user['username'], $attempts);
					return false;
				}
			} else {
				return false;
			}
		} catch ( PDOException $e ) {
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * @param $args array
	 * @return bool
	 */
	public function create( $args ) {
		try {
			if ( $this->usernameExists( $args['username'] ) ) {
				return false;
			}

			if ( !$this->validateUsername( $args['username'] ) ) {
				return false;
			}

			if ( !$this->validatePassword( $args['password'] ) ) {
				return false;
			}

			if ( !$this->validateEmail( $args['email'] ) ) {
				return false;
			}

			$username = $args['username'];
			$email = $args['email'];
			$password = $args['password'];
			$date = date("Y-m-d");

			$stmt = $this->db->prepare( "INSERT INTO $this->table SET username=:username, password=:password, email=:email, dateJoined=:dateJoined, loginAttempts=0");

			$stmt->bindParam(":username", $username, PDO::PARAM_STR );
			$stmt->bindParam(":password", $password, PDO::PARAM_STR );
			$stmt->bindParam(":email", $email, PDO::PARAM_STR );
			$stmt->bindParam(":dateJoined", $date, PDO::PARAM_STR );

			return $stmt->execute();
		} catch ( PDOException $e ) {
			echo $e->getMessage();
			return false;
		}
	}

	// TODO: Replace * with username, email, etc etc....
	public function read( $args = array() ) {
		try {
			if ( array_key_exists( 'id', $args ) && !empty( $args['id'] ) ) {
				$stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id=:id");
				$stmt->bindParam(":id", $args['id'], PDO::PARAM_INT);

			} else if ( array_key_exists( 'username', $args ) && !empty( $args['username'] ) ) {
				$args['username'] = "%".$args['username']."%";
				$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE username LIKE :username");
				$stmt->bindParam(":username", $args['username'], PDO::PARAM_STR);

			} else {
				$stmt = $this->db->prepare("SELECT * FROM $this->table");

			}

			$stmt->execute();

			$result = $stmt->fetchAll( PDO::FETCH_ASSOC );
			return $result;
		} catch ( PDOException $e ) {
			echo $e->getMessage();
			return null;
		}
	}

	public function update( $args ) {
		try {

		} catch ( PDOException $e ) {
			echo $e->getMessage();
		}
	}

	public function delete( $args ) {
		try {

		} catch ( PDOException $e ) {
			echo $e->getMessage();
		}
	}

}