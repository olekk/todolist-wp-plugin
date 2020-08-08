<?php

/**
 * Fired during plugin deactivation
 *
 * @link       aleksanderciesla.pl
 * @since      1.0.0
 *
 * @package    Todolist
 * @subpackage Todolist/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Todolist
 * @subpackage Todolist/includes
 * @author     Aleksander <Cieśla>
 */
class Todolist_Deactivator {

	private $table;
	public function __construct($tables) {
		$this->table = $tables->todolisttable();
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {

		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS ".$this->table);
	}

}
