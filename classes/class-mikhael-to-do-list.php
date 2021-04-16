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
        add_action('admin_enqueue_scripts', array($this, 'todolist_admin_enqueue_scripts'));
        add_action('wp_ajax_todolist_create_new_task', array($this, 'todolist_create_new_task'));
        add_action('wp_ajax_todolist_update_task', array($this, 'todolist_update_task'));
        add_action('wp_ajax_todolist_task_done', array($this, 'todolist_task_done'));
        add_action('wp_ajax_todolist_delete_task', array($this, 'todolist_delete_task'));
    }

    /**
     * Create the DB table
     */
    function todolist_create_table() {
        global $wpdb;

        if (!current_user_can('activate_plugins')) return;

        $table_name = $wpdb->prefix . 'todolist';
        $check_table = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");

        if ($check_table !== $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE " . $table_name . " (
			id   mediumint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			text varchar(9999) NOT NULL DEFAULT '',
			date varchar(50)  NOT NULL DEFAULT '',	
			priority varchar(50) NOT NULL DEFAULT '',
			status varchar(50) NOT NULL DEFAULT '',
			user_id int NOT NULL,
			PRIMARY KEY (id)
			) " . $charset_collate . ";";

            $prepared = $wpdb->prepare($sql);

            $wpdb->query($prepared);
        }
    }

    /**
     * Register a custom menu page.
     */
    function register_todolist_page() {
        add_menu_page('Custom Menu Title', 'To-do List', 'read', 'mikhael-to-do-list/views/todolist-main.php', '');
    }


    /**
     * Enqueue plugin's admin scripts and styles
     */
    function todolist_admin_enqueue_scripts() {
        wp_enqueue_style('todolist-styles', plugin_dir_url(__DIR__) . 'css/styles.css', array());
        wp_enqueue_script('todolist-scripts', plugin_dir_url(__DIR__) . 'js/scripts.js', array('jquery'));
        wp_add_inline_script('todolist-scripts', '
            var ajaxurl = "'. admin_url('admin-ajax.php') . '";
            var plugin_url = "' . plugin_dir_url(__DIR__) . '";
        ');
    }

    /**
     * Create a new task
     */
    function todolist_create_new_task() {
        global $wpdb;
        $user_id = get_current_user_id();

        $table_name  = $wpdb->prefix .'todolist';
        $inserted = $wpdb->insert($table_name, array(

            'text' => esc_html__($_POST['task_text']),
            'date' => esc_html__($_POST['task_deadline']),
            'priority' => esc_html__($_POST['task_prio']),
            'status' => 'pending',
            'user_id' => $user_id,

        ), array('%s', '%s', '%s', '%s', '%d'));

        if ($inserted) {
            echo json_encode([
                'result' => 'success',
                'task_id' => $wpdb->insert_id,
            ]);
        } else {
            echo json_encode([
                'result' => 'fail',
            ]);
        }


        wp_die();
    }

    /**
     * Update the task
     */
    function todolist_update_task() {
        global $wpdb;
        $user_id = get_current_user_id();

        $table_name  = $wpdb->prefix .'todolist';
        $updated = $wpdb->update($table_name, array(

            'text' => esc_html__($_POST['task_text']),
            'date' => esc_html__($_POST['task_deadline']),
            'priority' => esc_html__($_POST['task_prio']),
            'status' => 'pending',
            'user_id' => $user_id,

        ), array(
            'id' => $_POST['task_id'],
        ), array('%s', '%s', '%s', '%d', '%d'));

        if ($updated) {
            echo json_encode([
                'result' => 'success',
            ]);
        } else {
            echo json_encode([
                'result' => 'fail',
            ]);
        }


        wp_die();
    }

    /**
     * Update task status to done
     */
    function todolist_task_done() {
        global $wpdb;

        $table_name  = $wpdb->prefix .'todolist';
        $updated = $wpdb->update($table_name, array(
            'status' => 'done',
        ), array(
            'id' => $_POST['task_id'],
        ), array('%s', '%d'));

        if ($updated) {
            echo json_encode([
                'result' => 'success',
            ]);
        } else {
            echo json_encode([
                'result' => 'fail',
            ]);
        }


        wp_die();
    }

    /**
     * Delete a task
     */
    function todolist_delete_task() {
        global $wpdb;

        $table_name  = $wpdb->prefix .'todolist';
        $removed = $wpdb->delete($table_name, array(

            'id' => $_POST['task_id'],

        ), array('%d'));

        if ($removed) {
            echo json_encode([
                'result' => 'success',
            ]);
        } else {
            echo json_encode([
                'result' => 'fail',
            ]);
        }


        wp_die();
    }

    /**
     * Deactivation function
     */
    public static function deactivate_todolist() {
        MikhaelToDoList::todolist_delete_table();
    }

    /**
     * Delete the todolist db table
     */
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
