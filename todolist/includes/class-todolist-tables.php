<?php

/**
 * Defines plugin's table names
 *
 * @link       aleksanderciesla.pl
 * @since      1.0.0
 *
 * @package    Todolist
 * @subpackage Todolist/includes
 */

/**
 * Defines plugin's table names.
 *
 * @since      1.0.0
 * @package    Todolist
 * @subpackage Todolist/includes
 * @author     Aleksander <Cieśla>
 */
class Todolist_Tables {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function todolisttable() {
		
		global $wpdb;

		// return $wpdb->dbname." . ".$wpdb->base_prefix."todolist_tasks";
		return $wpdb->base_prefix."todolist_tasks";

	}

}
