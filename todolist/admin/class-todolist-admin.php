<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       aleksanderciesla.pl
 * @since      1.0.0
 *
 * @package    Todolist
 * @subpackage Todolist/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Todolist
 * @subpackage Todolist/admin
 * @author     Aleksander <Cieśla>
 */
class Todolist_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	private $table;

	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		require_once TODOLIST_PLUGIN_DIR . 'includes/class-todolist-tables.php';
		$tables = new Todolist_Tables();
		$this->table = $tables->todolisttable();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Todolist_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Todolist_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/styles.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Todolist_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Todolist_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, true );
		wp_localize_script($this->plugin_name, "todolist_ajax_url", admin_url("admin-ajax.php"));
	}
	
	public function todolist_menu() {

		add_menu_page("My ToDoList", "My ToDoList", "manage_options", "todolist-menu-slug", array($this, "todolist_page"), "dashicons-yes-alt", 30);

	}

	public function todolist_page() {
		include_once TODOLIST_PLUGIN_DIR.'/admin/partials/todolist-admin-display.php';
	}


	//Funcja wykonująca zapytania AJAX.

	public function todolist_ajax_handler() {
		global $wpdb;
		$param = isset($_REQUEST['param']) ? $_REQUEST['param'] : "";
		switch($param) {
			case 'addtask':
				$wpdb->insert($this->table, array(
					"status"=>FALSE,
					"content"=>esc_html($_REQUEST['content'])
				)); 

			break;
			case "savechanges":
				$wpdb->update($this->table, array(
					"content"=>esc_html($_REQUEST['content'])
				),array(
					'ID'=>$_REQUEST['id']
				));
			break;
			case "togglecheckbox":
				$wpdb->update($this->table, array(
					"status"=>esc_html($_REQUEST['status'])
				),array(
					'ID'=>$_REQUEST['id']
				));
			break;
			case "deletetask":
				$wpdb->delete($this->table, array('ID'=>$_REQUEST['id']));
			break;
		}

		//Po każdym wywołaniu, funkcja wysyła do klienta całą, zaktualizowaną tabelę pluginu.

		$myTable = $wpdb->get_results("SELECT * FROM ".$this->table);
		echo json_encode($myTable);

		wp_die();
	}
}
