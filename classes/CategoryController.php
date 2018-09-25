<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 25.09.2018
 * Time: 12.51
 */

class CategoryController extends ITable {

	public function __construct( PDO $db, string $table ) {
		parent::__construct( $db, $table );
	}

	/**
	 * DATABASE TABLE
	 *
	 * NAME
	 * categoryID		INT			PRIMARY KEY
	 * title			VARCHAR
	 */

	// TODO: Make create, read, update & delete functions for this class

}