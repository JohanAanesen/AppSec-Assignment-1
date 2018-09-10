<?php
/**
 * Created by PhpStorm.
 * User: svein
 * Date: 04.09.2018
 * Time: 21.19
 */

abstract class ITable {

	protected $db;
	protected $table;

	/**
	 * User constructor.
	 * @param $db PDO
	 * @param $table string
	 */
	public function __construct( $db, $table ) {
		$this->db = $db;
		$this->table = $table;
	}

}