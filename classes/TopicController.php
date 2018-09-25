<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 25.09.2018
 * Time: 11.26
 */

class TopicController extends ITable {

	public function __construct( PDO $db, string $table ) {
		parent::__construct( $db, $table );
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

}