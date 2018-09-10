<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 05.09.2018
 * Time: 12.00
 */

class Session {

	private $validTypes;

	public function __construct() {
		session_start();

		$this->validTypes = array(
			'success_msg',
			'error_msg',
			'warning_msg',
			'info_msg'
		);
	}

	public function __destruct() {
		// TODO: Implement __destruct() method.
		session_destroy();
	}

	public function set_userdata( $name, $value ) {
		$_SESSION[$name] = $value;

		if (isset($_SESSION[$name]['password'])) {
			unset($_SESSION[$name]['password']);
		}
	}

	public function get_userdata( $name ) {
		return $_SESSION[$name];
	}

	public function set_session($type, $message) {
		if ( !in_array($type, $this->validTypes) ) {
			return;
		}

		if ( isset( $_SESSION[$type] ) ) {
			return;
		}

		$_SESSION[$type] = $message;
	}

	public function get_session( $type ) {
		if ( isset( $_SESSION[$type] ) ) {
			return $_SESSION[$type];
		} else {
			return null;
		}
	}

	public function delete_session( $type ) {
		if ( isset( $_SESSION[$type] ) ) {
			unset( $_SESSION[$type] );
		}
	}

	public function has_session( $type ) {
		return isset($_SESSION[$type]) && !empty($_SESSION[$type]);
	}

	public function unset_all() {
		session_unset();
	}

}