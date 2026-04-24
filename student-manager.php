<?php
/**
 * Plugin Name: Student Manager
 * Description: Quản lý thông tin sinh viên với Custom Post Type, Meta Box và Shortcode.
 * Version: 1.0.0
 * Author: Nguyễn Đức Dương - 23810310091
 * Text Domain: student-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define Constants
define( 'STUDENT_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
define( 'STUDENT_MANAGER_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once STUDENT_MANAGER_PATH . 'includes/cpt-student.php';
require_once STUDENT_MANAGER_PATH . 'includes/meta-box.php';
require_once STUDENT_MANAGER_PATH . 'includes/shortcode.php';

// Enqueue styles
function sm_enqueue_styles() {
    wp_enqueue_style( 'sm-style', STUDENT_MANAGER_URL . 'assets/style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'sm_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'sm_enqueue_styles' );

/**
 * Flush rewrite rules on activation to prevent 404 errors for the CPT
 */
function sm_plugin_activation() {
    require_once STUDENT_MANAGER_PATH . 'includes/cpt-student.php';
    sm_register_student_cpt();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sm_plugin_activation' );

function sm_plugin_deactivation() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'sm_plugin_deactivation' );
