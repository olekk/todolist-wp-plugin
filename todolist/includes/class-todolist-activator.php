<?php

/**
 * Fired during plugin activation
 *
 * @link       aleksanderciesla.pl
 * @since      1.0.0
 *
 * @package    Todolist
 * @subpackage Todolist/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Todolist
 * @subpackage Todolist/includes
 * @author     Aleksander <Cieśla>
 */
class Todolist_Activator {

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
	public function activate() {
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;

		dbDelta("CREATE TABLE IF NOT EXISTS ".$this->table." ( `id` INT NOT NULL AUTO_INCREMENT , `status` tinyint(1) NOT NULL, `content` TEXT CHARACTER SET utf8 NOT NULL , PRIMARY KEY (`id`))");

		$wpdb->insert($this->table, array(
			"status"=>FALSE,
			"content"=>"Hire Aleksander"
		)); 
	}


}
