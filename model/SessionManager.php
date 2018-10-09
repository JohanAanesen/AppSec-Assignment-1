<?php

/**
 * Class SessionManager
 */
class SessionManager {

	const SUCCESS = 'success_msg';
	const WARNING = 'warning_msg';
	const ERROR = 'error_msg';

	/**
	 * Starts session
	 *
	 * @param string $name   - Name of the session
	 * @param int    $limit  - Lifetime of session, in seconds
	 * @param string $path   - Path on the domain where the session will work
	 * @param string $domain - Session domain
	 * @param bool   $secure - If true session will be sent over secure connections
	 */
	static function session_start( $name, $limit = 300, $path = '/', $domain = null, $secure = null ) {
		// Set the cookie name before we start
		session_name( $name . '_session' );

		// Set the domain to default to the current domain
		//$domain = isset( $domain ) ? $domain : isset( $_SERVER['SERVER_NAME'] );

		// Set SSL level
		$https = isset( $secure ) ? $secure : isset( $_SERVER['HTTPS'] );

		// Set the cookie settings and start the session
		session_set_cookie_params( $limit, $path, $domain, $https, true );
		session_start();

		// Make sure session hasn't expired, and destroy if it has
		if ( self::validate_session() ) {

			// Check if session is new or a hijack attempt
			if ( ! self::prevent_hijacking() ) {
				$_SESSION = array();
				$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				self::regenerate_session();

				// Give a 5% chance of session id changing or any request
			} else if ( rand( 1, 500 ) < 5 ) {
				self::regenerate_session();
			}
		} else {
			$_SESSION = array();
			session_destroy();
			session_start();
		}

		// Delete flash and log data after every request
		self::delete_flashdata();
	}

	/**
	 * Checks if there is any attempts on session hijacking
	 * @return bool
	 */
	static protected function prevent_hijacking() {
		// Check if IP-Address and User-Agent is set.
		if ( ! isset( $_SESSION['ip_address'] ) || ! isset( $_SESSION['user_agent'] ) ) {
			return false;
		}

		// Check if IP-Address is different from client
		if ( $_SESSION['ip_address'] != $_SERVER['REMOTE_ADDR'] ) {
			return false;
		}

		// Check if User-Agent is different from client
		if ( $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Regenerates new session
	 */
	static function regenerate_session() {
		// If this session is obsolete it means there already is a new id
		if ( isset( $_SESSION['OBSOLETE'] ) && $_SESSION['OBSOLETE'] == true ) {
			return;
		}

		// Set current session to expire in 60 seconds
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + ( 60 * 1 );

		// Create new session without destroying the old one
		session_regenerate_id( false );

		// Grab current ID and close both sessions to allow other scripts to use them
		$new_session = session_id();
		session_write_close();

		// Set session ID to the new one, and start it back up
		session_id( $new_session );
		session_start();

		// Now we unset the obsolete and expiration values for the session we want to keep
		unset( $_SESSION['OBSOLETE'] );
		unset( $_SESSION['EXPIRES'] );
	}

	/**
	 * Validates session, checks if it obsolete or expired
	 *
	 * @return bool
	 */
	static protected function validate_session() {
		// Checks if obsolete is set and if expires is not set
		if ( isset( $_SESSION['OBSOLETE'] ) && ! isset( $_SESSION['EXPIRES'] ) ) {
			return false;
		}

		// Checks if expires is set and if it has expired
		if ( isset( $_SESSION['EXPIRES'] ) && $_SESSION['EXPIRES'] < time() ) {
			return false;
		}

		return true;
	}

	/**
	 * Deletes all flashdata
	 */
	private static function delete_flashdata() {
		unset( $_SESSION['flash_data'] );
	}

	/**
	 * Sets flashdata to the session
	 *
	 * @param string $key   - Key name
	 * @param mixed  $value - Name
	 */
	static function set_flashdata( $key, $value ) {
		$_SESSION['flash_data'][$key] = $value;
	}

	/**
	 * Retrieves flashdata if it is set in the session
	 *
	 * @param string $name - Value name
	 * @return array|string|null    - Return value
	 */
	static function get_flashdata( $name = "" ) {
		if ( isset( $_SESSION['flash_data'] ) && empty( $name ) ) {
			return $_SESSION['flash_data'];
		}

		if ( isset( $_SESSION['flash_data'][$name] ) && ! empty( $name ) ) {
			return $_SESSION['flash_data'][$name];
		}

		return null;
	}

	/**
	 * Sets userdata to session
	 *
	 * @param mixed $value - Value
	 */
	static function set_userdata( $value ) {
		$_SESSION['user_data'] = $value;
	}

	/**
	 * Retrieves userdata if it is set
	 *
	 * @param string $key - Key name
	 * @return array|string|null    - Return value
	 */
	static function get_userdata( $key = "" ) {
		if ( isset( $_SESSION['user_data'] ) && empty( $key ) ) {
			return $_SESSION['user_data'];
		}

		if ( isset( $_SESSION['user_data'][$key] ) && ! empty( $key ) ) {
			return $_SESSION['user_data'][$key];
		}

		return null;
	}

	/**
	 * Deletes all userdata in the session
	 */
	static function delete_userdata() {
		unset( $_SESSION['user_data'] );
	}

}