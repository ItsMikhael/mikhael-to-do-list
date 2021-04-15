<?php
/**
 * Mikhael To-do list Plugin
 *
 * @package Mikhael To-do list Plugin
 */

/**
 * Plugin Name: To-do list
 * Plugin URI: http://github.com/ItsMikhael
 * Description: A simple to-do list.
 * Author: Mikhael
 * Author URI: http://github.com/ItsMikhael
 * Version: 1.0
 */

/**
 * Recommended WP Plugin security in case server is misconfigured.
 *
 * @see https://codex.wordpress.org/Writing_a_Plugin
 */
defined('ABSPATH') || die('No script kiddies please!');

require_once (plugin_dir_path(__FILE__) . 'classes/class-mikhael-to-do-list.php');

$mikhael_to_do_list = (isset($mikhael_to_do_list) && is_object($mikhael_to_do_list) ? $mikhael_to_do_list : new MikhaelToDoList());

register_deactivation_hook( __FILE__, array( 'MikhaelToDoList', 'deactivate_todolist' ) );
