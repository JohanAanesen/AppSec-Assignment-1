<?php

/**
 * Class Logger
 */
class Logger {

	const DEBUG = 1;
	const SUCCESS = 2;
	const WARNING = 3;
	const ERROR = 4;

	/**
	 * @var Logger
	 */
	static protected $instance;

	/**
	 * @var string
	 */
	static protected $filename;

	/**
	 * @var resource
	 */
	protected $file;

	/**
	 * Logger constructor.
	 */
	protected function __construct() {
		$folder = sprintf( 'logs/%s/%s/', date( 'Y' ), date( 'm' ) );

		if ( !file_exists( $folder ) ) {
			mkdir ( $folder, 0777, true );
		}

		if ( !$this->file = fopen( "logs/" . date('Y/m/' ) . self::get_filename(), "a+" ) ) {
			throw new RuntimeException( sprintf( "Could not open file \"%s\" for writing.", self::get_filename() ) );
		}
	}

	/**
	 * Logger deconstructor
	 */
	public function __destruct() {
		fclose( $this->file );
	}

	/**
	 *
	 */
	static protected function get_instance() {
		if ( !self::has_instance() ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	static protected function has_instance() {
		return self::$instance instanceof self;
	}

	static function set_filename( $filename ) {
		self::$filename = $filename;
	}

	static function get_filename() {
		if ( self::$filename == null ) {
			self::set_filename( sprintf( "%s.log", date( 'd' ) ) );
		}

		return self::$filename;
	}

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

		$message = sprintf( "%s %s\n", $date, $message );

		fwrite( $this->file, $message );
	}

	static public function write( $message, $level = self::DEBUG ) {
		self::get_instance()->write_line( $message, $level );
	}

}