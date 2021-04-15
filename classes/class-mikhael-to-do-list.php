<?php
/**
 * Mikhael To-do List
 *
 * @package Mikhael To-do List
 */

/**
 * Class MikhaelToDoList
 */
class MikhaelToDoList
{

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_init', array($this, 'todolist_create_table'));
        add_action('admin_menu', array($this, 'register_todolist_page'));
    }

    public static function deactivate_todolist() {
        MikhaelToDoList::todolist_delete_table();
    }

    /**
     * Register a custom menu page.
     */
    function register_todolist_page() {
        add_menu_page('Custom Menu Title', 'To-do List', 'read', 'mikhael-to-do-list/views/todolist-main.php', '');

    }

    /**
     * Create a DB table
     */
    static function todolist_create_table() {
        global $wpdb;

        if (!current_user_can('activate_plugins')) return;

        $table_name = $wpdb->prefix . 'todolist';
        $check_table = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");

        if ($check_table !== $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE " . $table_name . " (
			id   mediumint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			text varchar(9999) NOT NULL DEFAULT '',
			date varchar(200)  NOT NULL DEFAULT '',	
			status varchar(200) NOT NULL DEFAULT '',			
			PRIMARY KEY (id)
			) " . $charset_collate . ";";

            $wpdb->query($wpdb->prepare($sql));
        }
    }

    static function todolist_delete_table() {
        global $wpdb;

        if (!current_user_can('activate_plugins')) return;

        $table_name = $wpdb->prefix . 'todolist';
        $check_table = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");

        if ($check_table === $table_name) {
            $sql = "DROP TABLE " . $table_name . ";";
        }
        $wpdb->query($wpdb->prepare($sql));
    }
}
