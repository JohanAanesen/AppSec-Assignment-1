<?php

/**
 * Class Logger
 */
class Logger {

	/**
	 * @var number
	 */
	const DEBUG 	= 1;

	/**
	 * @var number
	 */
	const SUCCESS 	= 2;

	/**
	 * @var number
	 */
	const WARNING 	= 3;

	/**
	 * @var number
	 */
	const ERROR 	= 4;

	/**
	 * Skeleton object
	 *
	 * @var Logger
	 */
	static protected $instance;

	/**
	 * File name
	 *
	 * @var string
	 */
	static protected $filename;

	/**
	 * Resource object, contains the current logging-file
	 *
	 * @var resource
	 */
	protected $file;

	/**
	 * Directory path
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Logger constructor.
	 *
	 * Creates new folder and/or file for the current date, month and year.
	 */
	protected function __construct() {
		$this->path = sprintf( 'logs/%s/%s/', date( 'Y' ), date( 'm' ) );

		if ( !file_exists( $this->path ) ) {
			mkdir ( $this->path, 0777, true );
		}

		try {
			$this->file = fopen( "logs/" . date( "Y/m/" ) . self::get_filename(), "a+" );
		} catch ( RuntimeException $e ) {
			printf( "Could not open file \"%s\" for writing.", self::get_filename() );
		}
	}

	/**
	 * Logger deconstructor
	 *
	 * Closes file
	 */
	public function __destruct() {
		fclose( $this->file );
	}

	/**
	 * Return a static instance of the class
	 */
	static protected function get_instance() {
		if ( !self::has_instance() ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Checks if the instance is an instance of this class
	 *
	 * @return bool
	 */
	static protected function has_instance() {
		return self::$instance instanceof self;
	}

	/**
	 * Sets the file name
	 *
	 * @param string $filename			- Filename
	 */
	static function set_filename( $filename ) {
		self::$filename = $filename;
	}

	/**
	 * Returns file name
	 *
	 * @return string
	 */
	protected static function get_filename() {
		if ( self::$filename == null ) {
			self::set_filename( sprintf( "%s.log", date( 'd' ) ) );
		}

		return self::$filename;
	}

	/**
	 * Writes a line, with different type of message based on the level of the message.
	 *
	 * @param string $message			- Message
	 * @param number $level				- Type of message
	 */
	protected function write_line( $message, $level ) {
		$date = date( "d/m/Y H:i:s" );

		switch ( $level ) {
			case self::SUCCESS:
				$date = sprintf( "%s (SUCCESS) ::", $date );
				break;

			case self::WARNING:
				$date = sprintf( "%s (WARNING) ::", $date );
				break;

			case self::ERROR:
				$date = sprintf( "%s   (ERROR) ::", $date );
				break;

			case self::DEBUG:
				$date = sprintf( "%s   (DEBUG) ::", $date );
				break;

			default:
				$date = "";
				break;
		}

		$message = sprintf( "%s [%s] :: %s\n", $date, $_SERVER['REMOTE_ADDR'], $message );

		fwrite( $this->file, $message );
	}

	/**
	 * Calls the non-static write_line function, with a static function.
	 *
	 * @param string $message			- Message
	 * @param number $level				- Type of message
	 */
	static public function write( $message, $level = self::DEBUG ) {
		self::get_instance()->write_line( $message, $level );
	}

}