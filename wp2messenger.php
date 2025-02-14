<?php
/*
Plugin Name: WP2messenger
Plugin URI: https://salehweb.ir/wp2messenger
Description: Send messages to messenger apps from WordPress (started by eitaa).
Version: 0.1
Author: MJasia
Author URI: https://mjasia.ir
License: GPL2
Text Domain: wp2messenger
Domain Path: /languages
*/

// Load plugin textdomain for translations
function wp2messenger_load_textdomain() {
    load_plugin_textdomain('v', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'wp2messenger_load_textdomain');

// Activation hook
function wp2messenger_activate() {
    load_plugin_textdomain('wp2messenger', false, basename(dirname(__FILE__)) . '/languages');
    add_option('wp2messenger_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'wp2messenger_activate');

// add shortcode to display default form template
function wp2messenger_default_template_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/default.php';
    return ob_get_clean();
}
add_shortcode('default_template', 'wp2messenger_default_template_shortcode');

// Redirect to wizard
function wp2messenger_activation_redirect() {
    if (get_option('wp2messenger_do_activation_redirect', false)) {
        delete_option('wp2messenger_do_activation_redirect');
        if (!isset($_GET['activate-multi'])) {
            wp_safe_redirect(admin_url('admin.php?page=wp2messenger_wizard'));
            exit;
        }
    }
}
add_action('admin_init', 'wp2messenger_activation_redirect');

// Enqueue scripts and styles
function wp2messenger_enqueue_scripts() {
    wp_enqueue_style('wp2messenger-admin-css', plugin_dir_url(__FILE__) . 'assets/admin/css/admin-styles.css');
    wp_enqueue_style('wp2messenger-wizard-css', plugin_dir_url(__FILE__) . 'assets/admin/css/wizard-styles.css');
    wp_enqueue_script('wp2messenger-wizard-js', plugin_dir_url(__FILE__) . 'assets/admin/js/wizard.js', array('jquery'), null, true);
    wp_enqueue_style('wp2messenger-user-css', plugin_dir_url(__FILE__) . 'assets/user/css/user-styles.css');
    wp_enqueue_script('wp2messenger-form-js', plugin_dir_url(__FILE__) . 'assets/user/js/form-interactions.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'wp2messenger_enqueue_scripts');
add_action('wp_enqueue_scripts', 'wp2messenger_enqueue_scripts');

// Add menu and settings page
function wp2messenger_add_admin_menu() {
    add_menu_page(
        __('WP2messenger', 'wp2messenger'),
        __('WP2messenger', 'wp2messenger'),
        'manage_options',
        'wp2messenger',
        'wp2messenger_settings_page',
        'dashicons-email-alt',
        6
    );

    add_submenu_page(
        null,
        __('WP2messenger Setup Wizard', 'wp2messenger'),
        __('WP2messenger Setup Wizard', 'wp2messenger'),
        'manage_options',
        'wp2messenger_wizard',
        'wp2messenger_wizard_page'
    );
}
add_action('admin_menu', 'wp2messenger_add_admin_menu');

function wp2messenger_sanitize_text_field($input) {
    return sanitize_text_field(wp_unslash($input)); // unslash and sanitize value before save to options
}

// Register settings
function wp2messenger_register_settings() {
	
	register_setting('wp2messenger_options_group', 'token_eitaa_api', array('sanitize_callback' => 'wp2messenger_sanitize_text_field'));
	register_setting('wp2messenger_options_group', 'eitaa_channel_id', array('sanitize_callback' => 'wp2messenger_sanitize_text_field'));

}
add_action('admin_init', 'wp2messenger_register_settings');

// Settings page callback
function wp2messenger_settings_page() {
    include plugin_dir_path(__FILE__) . 'admin/settings.php';
}

// Wizard page callback
function wp2messenger_wizard_page() {
    include plugin_dir_path(__FILE__) . 'admin/wizard.php';
}

require_once plugin_dir_path( __FILE__ ) . 'includes/send-message.php';
?>
